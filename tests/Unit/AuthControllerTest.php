<?php

namespace Tests\Unit;

use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;
use Google_Client;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as ProviderUser;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test login method returns view when not authenticated
     */
    public function test_login_returns_view_when_not_authenticated()
    {
        $controller = new AuthController();
        $response = $controller->login();

        $this->assertEquals('auth.login', $response->getName());
    }

    /**
     * Test login method redirects to home when already authenticated
     */
    public function test_login_redirects_to_home_when_authenticated()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        Auth::login($user);

        $controller = new AuthController();
        $response = $controller->login();

        $this->assertEquals(302, $response->status());
        $this->assertStringEndsWith('/home', $response->headers->get('Location'));
    }

    /**
     * Test authenticate method with valid credentials
     */
    public function test_authenticate_with_valid_credentials()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now()
        ]);

        $request = Request::create('/login', 'POST', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        // Set session store for request
        $request->setLaravelSession(app('session.store'));

        $controller = new AuthController();
        $response = $controller->authenticate($request);

        $this->assertEquals(302, $response->status());
        $this->assertStringEndsWith('/home', $response->headers->get('Location'));
        $this->assertTrue(Auth::check());
    }

    /**
     * Test authenticate method with invalid credentials
     */
    public function test_authenticate_with_invalid_credentials()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        $request = Request::create('/login', 'POST', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        // Set session store for request
        $request->setLaravelSession(app('session.store'));

        $controller = new AuthController();
        $response = $controller->authenticate($request);

        $this->assertEquals(302, $response->status());
        $this->assertFalse(Auth::check());
    }

    /**
     * Test authenticate method with unverified email
     */
    public function test_authenticate_with_unverified_email()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => null
        ]);

        $request = Request::create('/login', 'POST', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        // Set session store for request
        $request->setLaravelSession(app('session.store'));

        $controller = new AuthController();
        $response = $controller->authenticate($request);

        $this->assertEquals(302, $response->status());
        $this->assertStringEndsWith('/email/verify', $response->headers->get('Location'));
        $this->assertTrue(Auth::check());
    }

    /**
     * Test logout method
     */
    public function test_logout()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        Auth::login($user);

        $request = Request::create('/logout', 'POST');

        // Set session store for request
        $request->setLaravelSession(app('session.store'));

        $controller = new AuthController();
        $response = $controller->logout($request);

        $this->assertEquals(302, $response->status());
        $this->assertStringEndsWith('/login', $response->headers->get('Location'));
        $this->assertFalse(Auth::check());
    }

    /**
     * Test redirectToGoogle method
     */
    public function test_redirect_to_google()
    {
        // Perbaiki mock Socialite
        Socialite::shouldReceive('driver')
            ->andReturnSelf();
        Socialite::shouldReceive('redirect')
            ->andReturn(response()->json(['redirect' => 'google_oauth_url']));

        $controller = new AuthController();
        $response = $controller->redirectToGoogle();

        $this->assertEquals(302, $response->status());
    }

    /**
     * Test handleGoogleCallback with One Tap credential
     * @skip - Requires real Google credentials for JWT verification
     */
    public function test_handle_google_callback_with_one_tap()
    {
        $this->markTestSkipped('Requires real Google credentials for JWT verification');
    }

    /**
     * Test handleGoogleCallback with Socialite OAuth
     */
    public function test_handle_google_callback_with_socialite()
    {
        // Mock Socialite user
        $mockSocialiteUser = $this->createMock(ProviderUser::class);
        $mockSocialiteUser->method('getId')->willReturn('google456');
        $mockSocialiteUser->method('getEmail')->willReturn('socialite@example.com');
        $mockSocialiteUser->method('getName')->willReturn('Socialite User');

        // Mock Socialite facade
        Socialite::shouldReceive('driver')
            ->andReturnSelf();
        Socialite::shouldReceive('user')
            ->andReturn($mockSocialiteUser);

        $request = Request::create('/auth/google/callback', 'GET');

        // Set session store for request
        $request->setLaravelSession(app('session.store'));

        $controller = new AuthController();
        $response = $controller->handleGoogleCallback($request);

        $this->assertEquals(302, $response->status());
        $this->assertStringEndsWith('/email/verify', $response->headers->get('Location'));
    }

    /**
     * Test handleGoogleCallback with invalid One Tap credential
     * @skip - Requires real Google credentials for JWT verification
     */
    public function test_handle_google_callback_with_invalid_one_tap()
    {
        $this->markTestSkipped('Requires real Google credentials for JWT verification');
    }

    /**
     * Test handleOneTapCallback method
     * @skip - Requires real Google credentials for JWT verification
     */
    public function test_handle_one_tap_callback()
    {
        $this->markTestSkipped('Requires real Google credentials for JWT verification');
    }

    /**
     * Test handleGoogleOAuthCallback method
     */
    public function test_handle_google_oauth_callback()
    {
        // Mock Socialite user
        $mockSocialiteUser = $this->createMock(ProviderUser::class);
        $mockSocialiteUser->method('getId')->willReturn('google456');
        $mockSocialiteUser->method('getEmail')->willReturn('oauth@example.com');
        $mockSocialiteUser->method('getName')->willReturn('OAuth User');

        // Mock Socialite facade
        Socialite::shouldReceive('driver')
            ->andReturnSelf();
        Socialite::shouldReceive('user')
            ->andReturn($mockSocialiteUser);

        $controller = new AuthController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('handleGoogleOAuthCallback');
        $method->setAccessible(true);

        $response = $method->invoke($controller);

        $this->assertEquals(302, $response->status());
        $this->assertStringEndsWith('/email/verify', $response->headers->get('Location'));
    }

    /**
     * Test handleOneTapCallback with unverified email
     * @skip - Requires real Google credentials for JWT verification
     */
    public function test_handle_one_tap_callback_with_unverified_email()
    {
        $this->markTestSkipped('Requires real Google credentials for JWT verification');
    }
}
