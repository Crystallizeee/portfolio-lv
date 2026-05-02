<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CybersecProfile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class SyncCybersecProfiles extends Command
{
    protected $signature = 'cybersec:sync {--platform= : Specific platform to sync (tryhackme/letsdefend)}';
    protected $description = 'Sync cybersecurity training profiles (TryHackMe & LetsDefend) - refreshes badge images and logs sync status';

    public function handle(): int
    {
        $this->info('🔄 Starting cybersecurity profile sync...');

        $query = CybersecProfile::query();

        if ($platform = $this->option('platform')) {
            $query->where('platform', $platform);
        }

        $profiles = $query->get();

        if ($profiles->isEmpty()) {
            $this->warn('No cybersec profiles found to sync.');
            return self::SUCCESS;
        }

        foreach ($profiles as $profile) {
            $this->syncProfile($profile);
        }

        $this->info('✅ Sync completed for ' . $profiles->count() . ' profile(s).');

        return self::SUCCESS;
    }

    protected function syncProfile(CybersecProfile $profile): void
    {
        $this->line("  → Syncing {$profile->platform_name}: @{$profile->username}");

        if ($profile->platform === 'tryhackme') {
            $this->syncTryHackMe($profile);
        } elseif ($profile->platform === 'letsdefend') {
            $this->syncLetsDefend($profile);
        }

        // Update the timestamp to track when last synced
        $profile->update([
            'custom_stats' => array_merge($profile->custom_stats ?? [], [
                'last_synced_at' => now()->toIso8601String(),
                'sync_status' => 'success',
            ]),
        ]);
    }

    protected function syncTryHackMe(CybersecProfile $profile): void
    {
        // 1. Verify the badge image is still accessible (cache-bust)
        $badgeUrl = "https://tryhackme-badges.s3.amazonaws.com/{$profile->username}.png";

        try {
            $response = Http::timeout(10)
                ->head($badgeUrl);

            if ($response->successful()) {
                $this->info("    ✓ THM badge image verified ({$badgeUrl})");

                // Store the badge verification timestamp for cache-busting on frontend
                $profile->update([
                    'custom_stats' => array_merge($profile->custom_stats ?? [], [
                        'badge_verified_at' => now()->toIso8601String(),
                        'badge_url' => $badgeUrl . '?t=' . now()->timestamp,
                    ]),
                ]);
            } else {
                $this->warn("    ⚠ THM badge image returned HTTP {$response->status()}");
                Log::warning("CybersecSync: THM badge image not accessible", [
                    'username' => $profile->username,
                    'status' => $response->status(),
                ]);
            }
        } catch (\Exception $e) {
            $this->error("    ✗ Failed to verify THM badge: {$e->getMessage()}");
            Log::error("CybersecSync: THM badge verification failed", [
                'username' => $profile->username,
                'error' => $e->getMessage(),
            ]);
        }

        // 2. Try to scrape public profile for basic stats using Python Playwright
        try {
            $scriptPath = base_path('scripts/scrape_cybersec.py');
            $result = Process::run("python {$scriptPath} --platform tryhackme --username {$profile->username}");

            if ($result->successful()) {
                $output = json_decode($result->output(), true);
                if ($output && $output['success']) {
                    $stats = $output['stats'];
                    
                    $profile->update([
                        'rank' => $stats['rank'] ?? ($stats['top_percent'] ?? $profile->rank),
                        'points' => $stats['points'] ?? $profile->points,
                        'rooms_completed' => $stats['rooms_completed'] ?? $profile->rooms_completed,
                        'badges_count' => $stats['badges_count'] ?? $profile->badges_count,
                        'streak' => $stats['streak'] ?? $profile->streak,
                        'top_percent' => isset($stats['top_percent']) ? str_replace(['Top ', '%'], '', $stats['top_percent']) : $profile->top_percent,
                    ]);
                    
                    $this->info("    ✓ THM stats updated via scraper");
                } else {
                    $this->warn("    ⚠ THM scraper error: " . ($output['error'] ?? 'Unknown error'));
                }
            } else {
                $this->warn("    ⚠ THM scraper process failed: " . $result->errorOutput());
            }
        } catch (\Exception $e) {
            $this->error("    ✗ THM scraper failed: {$e->getMessage()}");
        }
    }

    protected function syncLetsDefend(CybersecProfile $profile): void
    {
        try {
            $scriptPath = base_path('scripts/scrape_cybersec.py');
            $result = Process::run("python {$scriptPath} --platform letsdefend --username {$profile->username}");

            if ($result->successful()) {
                $output = json_decode($result->output(), true);
                if ($output && $output['success']) {
                    $stats = $output['stats'];
                    
                    $profile->update([
                        'rank' => $stats['rank'] ?? $profile->rank,
                        'points' => $stats['points'] ?? $profile->points,
                        'rooms_completed' => $stats['rooms_completed'] ?? $profile->rooms_completed,
                        'badges_count' => $stats['badges_count'] ?? $profile->badges_count,
                    ]);
                    
                    $this->info("    ✓ LetsDefend stats updated via scraper");
                } else {
                    $this->warn("    ⚠ LetsDefend scraper error: " . ($output['error'] ?? 'Unknown error'));
                    
                    // Fallback to basic URL check if scraper fails
                    $this->verifyLetsDefendUrl($profile);
                }
            } else {
                $this->warn("    ⚠ LetsDefend scraper process failed");
                $this->verifyLetsDefendUrl($profile);
            }
        } catch (\Exception $e) {
            $this->error("    ✗ LetsDefend scraper failed: {$e->getMessage()}");
        }
    }

    protected function verifyLetsDefendUrl(CybersecProfile $profile): void
    {
        $profileUrl = $profile->profile_url ?? $profile->generated_profile_url;
        try {
            $response = Http::timeout(10)->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ])->head($profileUrl);

            if ($response->successful() || $response->status() === 302) {
                $this->info("    ✓ LetsDefend profile URL verified");
            } else {
                $this->warn("    ⚠ LetsDefend profile URL returned HTTP {$response->status()}");
            }
        } catch (\Exception $e) {
            $this->line("    ℹ LetsDefend URL check skipped: {$e->getMessage()}");
        }
    }
}
