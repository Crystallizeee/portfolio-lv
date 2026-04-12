<?php

namespace App\Services;

/**
 * Anonymizes IP addresses using HMAC-SHA256 hashing.
 * 
 * Compliant with UU PDP (Undang-Undang Pelindungan Data Pribadi).
 * The hash is deterministic (same IP = same hash) for uniqueness checks,
 * but cannot be reversed to the original IP address.
 */
class IpAnonymizer
{
    /**
     * Hash an IP address using HMAC-SHA256 with the application key.
     *
     * @param string|null $ip The raw IP address
     * @return string|null The hashed IP (64 char hex string) or null
     */
    public static function hash(?string $ip): ?string
    {
        if (empty($ip)) {
            return null;
        }

        return hash_hmac('sha256', $ip, config('app.key'));
    }

    /**
     * Hash the current request's IP address.
     *
     * @return string|null
     */
    public static function hashRequest(): ?string
    {
        return self::hash(request()->ip());
    }
}
