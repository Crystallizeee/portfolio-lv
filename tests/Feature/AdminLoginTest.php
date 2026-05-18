<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_rate_limiting()
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $throttleKey = Str::lower('admin@example.com') . '|127.0.0.1';

        // Fake the client IP since Livewire requests might have different structure in tests
        request()->server->set('REMOTE_ADDR', '127.0.0.1');

        $component = Livewire::test(\App\Livewire\Admin\AdminLogin::class)
            ->set('email', 'admin@example.com')
            ->set('password', 'wrongpassword');

        for ($i = 0; $i < 5; $i++) {
            $component->call('login');
        }

        // 6th attempt should hit the rate limiter
        $component->call('login')
            ->assertHasErrors(['email']);

        $this->assertTrue(RateLimiter::tooManyAttempts($throttleKey, 5));
    }
}
