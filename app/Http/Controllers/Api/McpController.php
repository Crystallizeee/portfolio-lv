<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\McpMessage;
use App\Services\McpService;

class McpController extends Controller
{
    protected $mcpService;

    public function __construct(McpService $mcpService)
    {
        $this->mcpService = $mcpService;
    }

    /**
     * Establish an SSE connection for MCP.
     */
    public function sse(Request $request)
    {
        $sessionId = (string) Str::uuid();

        return response()->stream(function () use ($sessionId) {
            // Disable output buffering
            if (ob_get_level() > 0) {
                ob_end_clean();
            }

            // Send the initial endpoint event with the POST URL
            $postUrl = route('mcp.messages', ['sessionId' => $sessionId]);
            echo "event: endpoint\n";
            echo "data: {$postUrl}\n\n";
            flush();

            // Loop to check for new messages in the queue
            while (true) {
                // If connection is aborted, exit the loop
                if (connection_aborted()) {
                    break;
                }

                $messages = McpMessage::where('session_id', $sessionId)
                                      ->orderBy('id', 'asc')
                                      ->get();

                foreach ($messages as $message) {
                    echo "event: message\n";
                    // Payload is already an array from model casts, we encode it to string for SSE data
                    $payloadStr = json_encode($message->payload);
                    echo "data: {$payloadStr}\n\n";
                    flush();

                    // Delete after sending
                    $message->delete();
                }

                // Sleep to prevent tight loop CPU burn
                sleep(1);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no' // Prevent Nginx from buffering
        ]);
    }

    /**
     * Receive JSON-RPC requests from the MCP client.
     */
    public function messages(Request $request)
    {
        $sessionId = $request->query('sessionId');

        if (!$sessionId) {
            return response()->json(['error' => 'Missing sessionId parameter'], 400);
        }

        // Get the parsed JSON payload
        $rpcRequest = $request->json()->all();
        
        if (empty($rpcRequest)) {
            return response()->json(['error' => 'Invalid JSON-RPC request'], 400);
        }

        // Process the request using the service
        $rpcResponse = $this->mcpService->handleRequest($rpcRequest);

        if ($rpcResponse !== null) {
            // Queue the response to be picked up by the SSE loop
            McpMessage::create([
                'session_id' => $sessionId,
                'payload' => $rpcResponse
            ]);
        }

        // Return 202 Accepted, as per MCP over HTTP specification
        return response('', 202);
    }
}
