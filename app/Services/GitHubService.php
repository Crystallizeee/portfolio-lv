<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GitHubService
{
    protected ?string $token;
    protected ?string $username;

    public function __construct()
    {
        $this->token = config('services.github.token');
        $this->username = config('services.github.username');
    }

    public function isConfigured(): bool
    {
        return !empty($this->token) && !empty($this->username);
    }

    /**
     * Get contribution data for the last year using GitHub GraphQL API.
     * Returns an array of weeks, each containing days with contribution counts.
     */
    public function getContributions(): ?array
    {
        return Cache::remember('github_contributions', 3600, function () {
            if (!$this->isConfigured()) {
                return null;
            }

            try {
                $query = <<<GRAPHQL
                {
                    user(login: "{$this->username}") {
                        contributionsCollection {
                            contributionCalendar {
                                totalContributions
                                weeks {
                                    contributionDays {
                                        contributionCount
                                        date
                                        weekday
                                    }
                                }
                            }
                        }
                    }
                }
                GRAPHQL;

                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$this->token}",
                ])->post('https://api.github.com/graphql', [
                    'query' => $query,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $calendar = $data['data']['user']['contributionsCollection']['contributionCalendar'] ?? null;

                    if (!$calendar) {
                        return null;
                    }

                    return [
                        'total' => $calendar['totalContributions'],
                        'weeks' => $calendar['weeks'],
                    ];
                }
            } catch (\Exception $e) {
                report($e);
            }

            return null;
        });
    }

    /**
     * Get user profile stats.
     */
    public function getProfileStats(): ?array
    {
        return Cache::remember('github_profile_stats', 3600, function () {
            if (!$this->isConfigured()) {
                return null;
            }

            try {
                $query = <<<GRAPHQL
                {
                    user(login: "{$this->username}") {
                        repositories(first: 100, ownerAffiliations: OWNER) {
                            totalCount
                            nodes {
                                stargazerCount
                                primaryLanguage {
                                    name
                                    color
                                }
                            }
                        }
                        followers {
                            totalCount
                        }
                    }
                }
                GRAPHQL;

                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$this->token}",
                ])->post('https://api.github.com/graphql', [
                    'query' => $query,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $user = $data['data']['user'] ?? null;

                    if (!$user) {
                        return null;
                    }

                    $repos = $user['repositories']['nodes'] ?? [];
                    $totalStars = collect($repos)->sum('stargazerCount');

                    // Top languages
                    $languages = collect($repos)
                        ->whereNotNull('primaryLanguage')
                        ->groupBy('primaryLanguage.name')
                        ->map(fn($group) => [
                            'count' => $group->count(),
                            'color' => $group->first()['primaryLanguage']['color'] ?? '#8b949e',
                        ])
                        ->sortByDesc('count')
                        ->take(5);

                    return [
                        'repos' => $user['repositories']['totalCount'],
                        'stars' => $totalStars,
                        'followers' => $user['followers']['totalCount'],
                        'languages' => $languages->toArray(),
                    ];
                }
            } catch (\Exception $e) {
                report($e);
            }

            return null;
        });
    }
}
