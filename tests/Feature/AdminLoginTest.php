<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Admin\AdminLogin;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_rate_limiting(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
        ]);

        $throttleKey = Str::transliterate(Str::lower('admin@example.com').'|'.'127.0.0.1');

        RateLimiter::clear($throttleKey);

        request()->server->set('REMOTE_ADDR', '127.0.0.1');

        $component = Livewire::test(AdminLogin::class)
            ->set('email', 'admin@example.com')
            ->set('password', 'wrongpassword');

        // Fail 5 times
        for ($i = 0; $i < 5; $i++) {
            $component->call('login')
                      ->assertHasErrors(['email']);
        }

        // The 6th time should trigger rate limit message
        $component->call('login');

        $errors = $component->errors();
        $this->assertTrue($errors->has('email'), 'Expected email error, but got none');
        $this->assertStringContainsString('Terlalu banyak percobaan login', $errors->first('email'));
    }
}
