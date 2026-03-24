<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\TestCase;

class GoogleOAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_oauth_redirect(): void
    {
        $response = $this->get('/auth/google');

        $response->assertStatus(302);

        $redirectUrl = $response->headers->get('Location');
        $this->assertStringContainsString('accounts.google.com', $redirectUrl);
    }

    public function test_google_oauth_callback_with_new_user(): void
    {
        Notification::fake();

        $mockSocialiteUser = new SocialiteUser();
        $mockSocialiteUser->id = 'google123';
        $mockSocialiteUser->name = 'John Doe';
        $mockSocialiteUser->email = 'john@example.com';
        $mockSocialiteUser->avatar = 'https://example.com/avatar.jpg';

        Socialite::shouldReceive('driver->user')
            ->andReturn($mockSocialiteUser);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect(route('verification.notice'));
        $response->assertSessionHas('success', 'Silakan verifikasi email Anda terlebih dahulu.');

        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at);
        $this->assertEquals('google', $user->provider);
        $this->assertEquals('google123', $user->provider_id);

        // ✅ Use custom notification
        Notification::assertSentTo(
            $user,
            EmailVerificationNotification::class
        );

        $this->assertAuthenticatedAs($user);
    }

    public function test_google_oauth_callback_with_existing_user(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'provider' => null,
            'provider_id' => null,
            'email_verified_at' => now(),
        ]);

        $mockSocialiteUser = new SocialiteUser();
        $mockSocialiteUser->id = 'google123';
        $mockSocialiteUser->name = 'John Doe';
        $mockSocialiteUser->email = 'john@example.com';
        $mockSocialiteUser->avatar = 'https://example.com/avatar.jpg';

        Socialite::shouldReceive('driver->user')
            ->andReturn($mockSocialiteUser);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/home');

        $user->refresh();
        $this->assertEquals('google', $user->provider);
        $this->assertEquals('google123', $user->provider_id);

        $this->assertAuthenticatedAs($user);
    }

    public function test_google_oauth_callback_with_existing_google_user(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'name' => 'John Doe',
            'provider' => 'google',
            'provider_id' => 'google123',
            'email_verified_at' => now(),
        ]);

        $mockSocialiteUser = new SocialiteUser();
        $mockSocialiteUser->id = 'google123';
        $mockSocialiteUser->name = 'John Doe Updated';
        $mockSocialiteUser->email = 'john@example.com';
        $mockSocialiteUser->avatar = 'https://example.com/avatar.jpg';

        Socialite::shouldReceive('driver->user')
            ->andReturn($mockSocialiteUser);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    public function test_google_oauth_callback_with_existing_user_email_not_verified(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'provider' => null,
            'provider_id' => null,
            'email_verified_at' => null,
        ]);

        $mockSocialiteUser = new SocialiteUser();
        $mockSocialiteUser->id = 'google123';
        $mockSocialiteUser->name = 'John Doe';
        $mockSocialiteUser->email = 'john@example.com';
        $mockSocialiteUser->avatar = 'https://example.com/avatar.jpg';

        Socialite::shouldReceive('driver->user')
            ->andReturn($mockSocialiteUser);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect(route('verification.notice'));
        $response->assertSessionHas('warning', 'Silakan verifikasi email Anda terlebih dahulu.');

        $user->refresh();
        $this->assertEquals('google', $user->provider);
        $this->assertEquals('google123', $user->provider_id);
        $this->assertNull($user->email_verified_at);

        Notification::assertNothingSent();

        $this->assertAuthenticatedAs($user);
    }

    public function test_google_oauth_callback_with_error(): void
    {
        Socialite::shouldReceive('driver->user')
            ->andThrow(new \Exception('Google OAuth error'));

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/login');
        $response->assertSessionHas('error', 'Login with Google failed. Please try again.');
    }

    public function test_google_oauth_callback_without_email(): void
    {
        $mockSocialiteUser = new SocialiteUser();
        $mockSocialiteUser->id = 'google123';
        $mockSocialiteUser->name = 'John Doe';
        $mockSocialiteUser->email = null;
        $mockSocialiteUser->avatar = 'https://example.com/avatar.jpg';

        Socialite::shouldReceive('driver->user')
            ->andReturn($mockSocialiteUser);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/login');
        $response->assertSessionHas('error', 'Login with Google failed. Please try again.');

        $this->assertDatabaseMissing('users', [
            'name' => 'John Doe',
        ]);
    }
}
