<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Minishlink\WebPush\VAPID;

class GenerateVapidKeys extends Command
{
    protected $signature = 'webpush:vapid';

    protected $description = 'Generate VAPID public/private keys for PWA Web Push notifications';

    public function handle(): int
    {
        $keys = VAPID::createVapidKeys();

        $this->info('Add these keys to your .env file:');
        $this->line('');
        $this->line('VAPID_SUBJECT=' . config('app.url'));
        $this->line('VAPID_PUBLIC_KEY=' . $keys['publicKey']);
        $this->line('VAPID_PRIVATE_KEY=' . $keys['privateKey']);
        $this->line('');

        return self::SUCCESS;
    }
}
