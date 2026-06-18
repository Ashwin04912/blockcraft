<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_form_is_displayed_to_guests()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_authenticated_user_is_redirected_away_from_login_form()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('login'));

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email'    => 'admin@blockcraft.test',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post(route('login.submit'), [
            'email'    => 'admin@blockcraft.test',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_password()
    {
        User::factory()->create([
            'email'    => 'admin@blockcraft.test',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post(route('login.submit'), [
            'email'    => 'admin@blockcraft.test',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_fails_for_unknown_email()
    {
        $response = $this->post(route('login.submit'), [
            'email'    => 'nobody@blockcraft.test',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_requires_email_and_password()
    {
        $response = $this->post(route('login.submit'), []);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_authenticated_user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_guest_cannot_logout()
    {
        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login'));
    }
}
