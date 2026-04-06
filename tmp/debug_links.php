<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::first();
echo "--- Current Admin Details ---\n";
echo "ID: " . $user->id . "\n";
echo "Email: " . $user->email . "\n";
echo "LinkedIn: [" . $user->linkedin . "]\n";
echo "GitHub: [" . $user->github . "]\n";
echo "---------------------------\n";
