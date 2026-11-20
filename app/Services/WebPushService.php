<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PushSubscription;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class WebPushService
{
    /**
     * Send a Web Push notification to all subscribed devices for a published post.
     */
    public function notifyNewPost(Post $post): int
    {
        $publicKey = config('services.webpush.public_key');
        $privateKey = config('services.webpush.private_key');
        $subject = config('services.webpush.subject') ?: config('app.url');

        if (! $publicKey || ! $privateKey || ! $subject) {
            return 0;
        }

        $subscriptions = PushSubscription::all();
        if ($subscriptions->isEmpty()) {
            return 0;
        }

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => $subject,
                'publicKey' => $publicKey,
                'privateKey' => $privateKey,
            ],
        ]);

        $payload = json_encode([
            'title' => 'New Post: ' . $post->title,
            'body' => $post->excerpt ?: 'Check out the latest article on benidictus.my.id',
            'url' => route('blog.show', $post->slug),
            'icon' => '/apple-touch-icon.png',
            'badge' => '/favicon.ico',
        ]);

        foreach ($subscriptions as $sub) {
            $webPush->queueNotification(
                Subscription::create([
                    'endpoint' => $sub->endpoint,
                    'publicKey' => $sub->public_key,
                    'authToken' => $sub->auth_token,
                    'contentEncoding' => $sub->content_encoding ?: 'aesgcm',
                ]),
                $payload
            );
        }

        $sent = 0;
        $expiredEndpoints = [];

        foreach ($webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();

            if ($report->isSuccess()) {
                $sent++;
            } elseif ($report->isSubscriptionExpired()) {
                $expiredEndpoints[] = $endpoint;
            } else {
                Log::warning('WebPush delivery failed', [
                    'endpoint' => $endpoint,
                    'reason' => $report->getReason(),
                ]);
            }
        }

        if (! empty($expiredEndpoints)) {
            PushSubscription::whereIn('endpoint', $expiredEndpoints)->delete();
        }

        return $sent;
    }
}
