<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$apiKey  = config('services.ollama.key');
$baseUrl = config('services.ollama.base_url');
$chat    = config('services.ollama.model');
$seo     = config('services.ollama.model_seo');

echo "URL: $baseUrl\nChat model: $chat\nSEO model: $seo\n\n";

$test = function($model, $message) use ($apiKey, $baseUrl) {
    $r = \Illuminate\Support\Facades\Http::withToken($apiKey)
        ->withHeaders(['Content-Type' => 'application/json'])
        ->timeout(30)
        ->post("$baseUrl/chat/completions", [
            'model'      => $model,
            'messages'   => [['role' => 'user', 'content' => $message]],
            'max_tokens' => 40,
            'stream'     => false,
        ]);
    echo "[$model] Status: " . $r->status() . "\n";
    echo $r->ok()
        ? "Reply: " . trim($r->json()['choices'][0]['message']['content'] ?? 'no content') . "\n"
        : "Error: " . $r->body() . "\n";
    echo "\n";
};

echo "=== Chatbot Model ===\n";
$test($chat, 'Say hello in 5 words only.');

echo "=== SEO Model ===\n";
$test($seo, 'Say hello in 5 words only.');
