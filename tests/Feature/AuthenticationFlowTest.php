<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuthenticationFlowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'session.driver' => 'array', 'cache.default' => 'array']);
        DB::purge('sqlite');
        Artisan::call('migrate', ['--force' => true]);
    }

    private function account(string $role = 'user'): User
    {
        return User::create(['name' => 'Pengguna', 'email' => 'login@example.test', 'password' => 'password123', 'role' => $role]);
    }

    public function test_web_login_and_logout(): void
    {
        $user = $this->account();
        $this->post('/login', ['email' => $user->email, 'password' => 'password123'])->assertRedirect('/');
        $this->assertAuthenticatedAs($user, 'web');
        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest('web');
    }

    public function test_web_login_preserves_intended_destination_and_rejects_wrong_password(): void
    {
        $user = $this->account();
        $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'wrong'])->assertSessionHasErrors('email');
        $this->assertGuest('web');
        $this->withSession(['url.intended' => url('/profile')])->post('/login', ['email' => $user->email, 'password' => 'password123'])->assertRedirect('/profile');
    }

    public function test_admin_login_redirects_to_dashboard(): void
    {
        $user = $this->account('admin');
        $this->post('/login', ['email' => $user->email, 'password' => 'password123'])->assertRedirect('/admin/dashboard');
    }

    public function test_web_registration_is_available(): void
    {
        $this->post('/register', ['name' => 'Baru', 'email' => 'new@example.test', 'password' => 'password123', 'password_confirmation' => 'password123'])->assertRedirect('/');
        $this->assertAuthenticated('web');
    }

    public function test_registration_page_submits_to_web_registration_with_csrf(): void
    {
        $this->get('/register')->assertOk()
            ->assertSee('method="POST" action="/register"', false)
            ->assertSee('name="_token"', false)
            ->assertDontSee("fetch('/api/register'", false)
            ->assertDontSee('localStorage.setItem', false);
    }

    public function test_registration_errors_are_displayed_without_preserving_passwords(): void
    {
        $this->from('/register')->post('/register', [
            'name' => 'Nama Baru', 'email' => 'invalid',
            'password' => 'short', 'password_confirmation' => 'different',
        ])->assertRedirect('/register')->assertSessionHasErrors(['email', 'password'])
            ->assertSessionMissing('_old_input.password')
            ->assertSessionMissing('_old_input.password_confirmation');
        $this->get('/register')->assertOk()->assertSee('Format email tidak valid.')
            ->assertSee('value="Nama Baru"', false);
        $this->assertGuest('web');
    }

    public function test_api_login_is_stateless_and_keeps_other_device_tokens(): void
    {
        $user = $this->account();
        $other = $user->createToken('other-device')->accessToken;
        $response = $this->postJson('/api/v1/login', ['email' => $user->email, 'password' => 'password123'])->assertOk()->assertJsonPath('token_type', 'Bearer');
        $this->assertGuest('web');
        $this->assertDatabaseHas('personal_access_tokens', ['id' => $other->id]);
        $this->withToken($response->json('token'))->getJson('/api/v1/me')->assertOk()->assertJsonPath('user.id', $user->id);
    }

    public function test_api_validation_and_unauthenticated_requests_return_json_without_accept_header(): void
    {
        $this->post('/api/v1/login', ['email' => 'invalid', 'password' => []])->assertStatus(422)->assertJsonValidationErrors(['email', 'password']);
        $this->get('/api/v1/me')->assertUnauthorized()->assertJsonStructure(['message']);
    }

    public function test_api_token_can_be_refreshed_and_logged_out(): void
    {
        $user = $this->account();
        $old = $user->createToken('device');
        $response = $this->withToken($old->plainTextToken)->postJson('/api/v1/refresh')->assertOk();
        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $old->accessToken->id]);
        // Reset cached guards to simulate a separate HTTP request.
        app('auth')->forgetGuards();
        $this->withToken($response->json('token'))->postJson('/api/v1/logout')->assertOk();
        $this->assertSame(0, $user->tokens()->count());
    }

    public function test_api_registration_does_not_require_a_web_session(): void
    {
        $this->postJson('/api/v1/register', ['name' => 'API User', 'email' => 'mobile@example.test', 'password' => 'password123', 'password_confirmation' => 'password123'])->assertCreated()->assertJsonPath('user.role', 'user')->assertJsonStructure(['token']);
        $this->assertGuest('web');
    }
}
