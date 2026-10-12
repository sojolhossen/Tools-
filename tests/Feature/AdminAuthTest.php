<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_user_is_forbidden_from_admin_area(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_administrator_can_access_dashboard(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Platform Overview');
    }

    public function test_administrator_can_login_with_valid_credentials(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('StrongSecret123!'),
            'is_admin' => true,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'StrongSecret123!',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'admin.login',
        ]);
    }

    public function test_invalid_login_fails_with_generic_error(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'unknown@example.com',
            'password' => 'WrongPassword!',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_login_rate_limiting_enforced(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', [
                'email' => 'brute@example.com',
                'password' => 'WrongPassword!',
            ]);
        }

        // 6th attempt should be blocked by rate limiter
        $response = $this->post('/admin/login', [
            'email' => 'brute@example.com',
            'password' => 'WrongPassword!',
        ]);

        $response->assertSessionHasErrors(['email']);
        $errors = session('errors')->get('email');
        $this->assertStringContainsString('Too many login attempts', $errors[0]);
    }

    public function test_administrator_can_logout(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post('/admin/logout');
        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_cli_admin_create_command_provisions_admin(): void
    {
        $this->artisan('admin:create', [
            '--email' => 'cli_admin@example.com',
            '--name' => 'CLI Admin',
            '--password' => 'SecurePass12345!',
        ])->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email' => 'cli_admin@example.com',
            'is_admin' => true,
        ]);
    }

    public function test_cli_admin_create_command_prevents_accidental_duplicates_without_force(): void
    {
        User::factory()->create(['is_admin' => true]);

        $this->artisan('admin:create', [
            '--email' => 'duplicate@example.com',
            '--name' => 'Duplicate',
            '--password' => 'SecurePass12345!',
        ])->assertExitCode(1);
    }

    public function test_public_registration_is_unavailable(): void
    {
        $this->get('/register')->assertStatus(404);
        $this->get('/admin/register')->assertStatus(404);
        $this->post('/register')->assertStatus(404);
        $this->post('/admin/register')->assertStatus(404);
    }
}
