<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Skill;
use Illuminate\Support\Facades\Log;

class McpService
{
    /**
     * Handle the incoming JSON-RPC request.
     */
    public function handleRequest(array $request): ?array
    {
        $method = $request['method'] ?? '';
        $id = $request['id'] ?? null;
        $params = $request['params'] ?? [];

        Log::info("MCP Request: {$method}", ['id' => $id, 'params' => $params]);

        $result = null;

        try {
            switch ($method) {
                case 'initialize':
                    $result = $this->handleInitialize($params);
                    break;
                case 'notifications/initialized':
                    // Just acknowledge, no response needed for notifications usually,
                    // but we might want to return null to avoid sending a response payload.
                    return null;
                case 'tools/list':
                    $result = $this->handleToolsList();
                    break;
                case 'tools/call':
                    $result = $this->handleToolsCall($params);
                    break;
                default:
                    // Method not found
                    if ($id !== null) {
                        return [
                            'jsonrpc' => '2.0',
                            'id' => $id,
                            'error' => [
                                'code' => -32601,
                                'message' => "Method not found: {$method}"
                            ]
                        ];
                    }
                    return null;
            }

            if ($id !== null) {
                return [
                    'jsonrpc' => '2.0',
                    'id' => $id,
                    'result' => $result
                ];
            }
        } catch (\Exception $e) {
            Log::error("MCP Error: " . $e->getMessage());
            if ($id !== null) {
                return [
                    'jsonrpc' => '2.0',
                    'id' => $id,
                    'error' => [
                        'code' => -32500,
                        'message' => $e->getMessage()
                    ]
                ];
            }
        }

        return null;
    }

    protected function handleInitialize(array $params)
    {
        return [
            'protocolVersion' => '2024-11-05',
            'serverInfo' => [
                'name' => 'laravel-portfolio-mcp',
                'version' => '1.0.0'
            ],
            'capabilities' => [
                'tools' => [
                    'listChanged' => false
                ],
                // Add resources or prompts here in the future
            ]
        ];
    }

    protected function handleToolsList()
    {
        return [
            'tools' => [
                [
                    'name' => 'get_projects',
                    'description' => 'Retrieve a list of public portfolio projects',
                    'inputSchema' => [
                        'type' => 'object',
                        'properties' => [
                            'limit' => [
                                'type' => 'integer',
                                'description' => 'Number of projects to return (default 5)'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'get_skills',
                    'description' => 'Retrieve the list of technical skills',
                    'inputSchema' => [
                        'type' => 'object',
                        'properties' => [
                            'category' => [
                                'type' => 'string',
                                'description' => 'Filter by category (e.g., Programming, DevOps). Optional.'
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    protected function handleToolsCall(array $params)
    {
        $toolName = $params['name'] ?? '';
        $args = $params['arguments'] ?? [];

        switch ($toolName) {
            case 'get_projects':
                $limit = $args['limit'] ?? 5;
                // Assuming Project model exists and has a scope or is just simple
                $projects = Project::where('is_published', true)
                                   ->orderBy('created_at', 'desc')
                                   ->take($limit)
                                   ->get(['title', 'slug', 'description', 'tech_stack', 'github_url', 'live_url'])
                                   ->toArray();
                
                return [
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => json_encode($projects, JSON_PRETTY_PRINT)
                        ]
                    ]
                ];

            case 'get_skills':
                $query = Skill::query();
                if (!empty($args['category'])) {
                    $query->where('category', $args['category']);
                }
                
                $skills = $query->get(['name', 'category', 'proficiency'])
                                ->toArray();
                
                return [
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => json_encode($skills, JSON_PRETTY_PRINT)
                        ]
                    ]
                ];

            default:
                throw new \Exception("Tool {$toolName} is not implemented.");
        }
    }
}
