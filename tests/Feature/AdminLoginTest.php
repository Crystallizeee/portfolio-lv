<?php

namespace Tests\Feature;

use App\Livewire\Admin\AdminLogin;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_rate_limiting()
    {
        $component = Livewire::withQueryParams([])
            ->test(AdminLogin::class)
            ->set('email', 'test@example.com')
            ->set('password', 'wrongpassword');

        for ($i = 0; $i < 5; $i++) {
            $component->call('login');
        }

        $component->call('login')
            ->assertHasErrors(['email']);

        $errors = $component->errors()->get('email');
        $this->assertStringContainsString('Terlalu banyak percobaan login', $errors[0]);
    }
}
