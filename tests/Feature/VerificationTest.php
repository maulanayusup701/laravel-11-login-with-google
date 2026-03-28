<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class VerificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test verification notice page loads for unverified user.
     */
    public function test_verification_notice_page_loads_for_unverified_user(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/email/verify');

        $response->assertStatus(200);
        $response->assertViewIs('auth.verify-email');
    }

    /**
     * Test verification notice page redirects for verified user.
     */
    public function test_verification_notice_page_redirects_for_verified_user(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/email/verify');

        $response->assertStatus(302);
    }

    /**
     * Test email verification with valid hash.
     */
    public function test_email_verification_with_valid_hash(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $hash = sha1($user->getEmailForVerification());
        $response = $this->get("/email/verify/{$user->id}/{$hash}");

        $response->assertRedirect('/home');
        $response->assertSessionHas('success', 'Email Anda berhasil diverifikasi. Selamat datang!');

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    /**
     * Test email verification with invalid hash.
     */
    public function test_email_verification_with_invalid_hash(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->get("/email/verify/{$user->id}/invalid-hash");

        $response->assertRedirect('/login');
        $response->assertSessionHas('error', 'Link verifikasi tidak valid.');
    }

    /**
     * Test email verification with non-existent user.
     */
    public function test_email_verification_with_nonexistent_user(): void
    {
        $response = $this->get('/email/verify/999/valid-hash');

        $response->assertRedirect('/login');
        $response->assertSessionHas('error', 'Link verifikasi tidak valid.');
    }

    /**
     * Test email verification already verified.
     */
    public function test_email_verification_already_verified(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $hash = sha1($user->getEmailForVerification());
        $response = $this->get("/email/verify/{$user->id}/{$hash}");

        $response->assertRedirect('/home');
        $response->assertSessionHas('success', 'Email Anda sudah diverifikasi.');
    }

    /**
     * Test resend verification email.
     */
    public function test_resend_verification_email(): void
    {
        Notification::fake();

        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->post('/email/resend');

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Link verifikasi telah dikirim ke email Anda.');

        Notification::assertSentTo(
            $user,
            \App\Notifications\EmailVerificationNotification::class
        );
    }

    /**
     * Test resend verification email for verified user.
     */
    public function test_resend_verification_email_for_verified_user(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->post('/email/resend');

        $response->assertRedirect('/home');
    }

    /**
     * Test verification page requires authentication.
     */
    public function test_verification_page_requires_authentication(): void
    {
        $response = $this->get('/email/verify');

        $response->assertRedirect('/login');
    }
}
