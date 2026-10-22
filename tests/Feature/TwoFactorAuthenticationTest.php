<?php

namespace Tests\Feature;

use App\Models\User;
use App\Utils\SecurityUtil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class TwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_2fa_logs_in_directly(): void
    {
        $user = User::factory()->create([
            'email' => 'regular@example.com',
            'password' => Hash::make('password123'),
            'two_factor_secret' => null,
        ]);

        $response = $this->post(route('login'), [
            'email' => 'regular@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_with_2fa_is_redirected_to_2fa_prompt(): void
    {
        config(['google2fa.enabled' => true]);

        $secret = SecurityUtil::generateTwoFactorSecret();
        $user = User::factory()->create([
            'email' => 'secure@example.com',
            'password' => Hash::make('password123'),
            'two_factor_secret' => $secret,
        ]);

        $response = $this->post(route('login'), [
            'email' => 'secure@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('2fa'));
        $response->assertSessionHas('2fa:user:id', $user->id);
        $this->assertGuest();
    }

    public function test_cannot_access_2fa_page_without_active_challenge(): void
    {
        $response = $this->get(route('2fa'));

        $response->assertRedirect(route('login'));
    }

    public function test_2fa_verification_fails_with_invalid_code(): void
    {
        $secret = SecurityUtil::generateTwoFactorSecret();
        $user = User::factory()->create([
            'two_factor_secret' => $secret,
        ]);

        $response = $this->withSession(['2fa:user:id' => $user->id])
            ->post(route('2fa.verify'), [
                'code' => '000000',
            ]);

        $response->assertSessionHasErrors('code');
        $this->assertGuest();
    }

    public function test_2fa_verification_succeeds_with_valid_code(): void
    {
        $google2fa = new Google2FA;
        $secret = $google2fa->generateSecretKey();
        $validCode = $google2fa->getCurrentOtp($secret);

        $user = User::factory()->create([
            'two_factor_secret' => $secret,
        ]);

        $response = $this->withSession(['2fa:user:id' => $user->id])
            ->post(route('2fa.verify'), [
                'code' => $validCode,
            ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }
}
