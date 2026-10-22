<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utils\SecurityUtil;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TwoFactorAuthController extends Controller
{
    /**
     * Show the 2FA verification form.
     */
    public function index(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('2fa:user:id')) {
            return redirect()->route('login');
        }

        return view('auth.2fa');
    }

    /**
     * Verify the 2FA code and log in the user.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! $request->session()->has('2fa:user:id')) {
            return redirect()->route('login');
        }

        $request->validate([
            'code' => 'required|string',
        ]);

        $user = User::findOrFail($request->session()->get('2fa:user:id'));

        $isValid = SecurityUtil::verifyTwoFactorCode($user->two_factor_secret, $request->code);

        if (! $isValid) {
            return back()->withErrors([
                'code' => 'The provided two-factor authentication code is invalid.',
            ]);
        }

        $remember = (bool) $request->session()->get('2fa:auth:remember', false);

        $request->session()->forget(['2fa:user:id', '2fa:auth:attempt', '2fa:auth:remember']);
        $request->session()->regenerate();

        Auth::login($user, $remember);

        return redirect()->intended('/dashboard');
    }
}
