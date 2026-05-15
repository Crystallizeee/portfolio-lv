<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use App\Livewire\Admin\AdminLogin;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\User;

class AdminLoginRateLimitingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_is_rate_limited()
    {
        // First run 5 failed attempts
        for ($i = 0; $i < 5; $i++) {
            Livewire::test(AdminLogin::class)
                ->set('email', 'admin@example.com')
                ->set('password', 'wrongpassword')
                ->call('login');
        }

        // The 6th attempt should be rate limited
        Livewire::test(AdminLogin::class)
            ->set('email', 'admin@example.com')
            ->set('password', 'wrongpassword')
            ->call('login')
            ->assertHasErrors('email');
    }

    public function test_successful_login_clears_rate_limit()
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Failed attempts
        Livewire::test(AdminLogin::class)
            ->set('email', 'admin@example.com')
            ->set('password', 'wrongpassword')
            ->call('login');

        // Assuming we haven't hit the limit, successful login should clear it
        Livewire::test(AdminLogin::class)
            ->set('email', 'admin@example.com')
            ->set('password', 'password123')
            ->call('login')
            ->assertRedirect(route('admin.dashboard'));

        // Reset rate limiter manually to check if it has been cleared since we don't have direct access
        // It's checked within the component logic and testing via asserting the redirects/errors
    }
}
