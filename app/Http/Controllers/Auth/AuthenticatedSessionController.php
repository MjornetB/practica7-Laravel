<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Inicia o increment el comptador de intents de login
        $attempts = session()->increment('login_attempts', 1);

        // Si la variable de intents de login es més gran que 3, valida el reCAPTCHA
        if ($attempts > 3) {
            $request->validate([
                'g-recaptcha-response' => 'required|captcha',
            ], [
                'g-recaptcha-response.required' => 'Por favor, complete el reCAPTCHA.',
                'g-recaptcha-response.captcha' => 'Error al validar el reCAPTCHA. Inténtalo de nuevo.',
            ]);
        }

        try {
            $request->authenticate();

            $request->session()->regenerate();

            // Si la autenticació és correcta, esborra el comptador de intents de login
            session()->forget('login_attempts');

            return redirect()->intended(RouteServiceProvider::HOME);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
