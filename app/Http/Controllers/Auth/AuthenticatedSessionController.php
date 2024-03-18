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
        // Inicia o incrementa el contador de intentos de inicio de sesión
        $attempts = session()->increment('login_attempts', 1);

        // Valida el reCAPTCHA solo después de 3 intentos fallidos
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

            // Reinicia el contador de intentos después de un inicio de sesión exitoso
            session()->forget('login_attempts');

            return redirect()->intended(RouteServiceProvider::HOME);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si la autenticación falla, el contador se incrementará automáticamente
            // debido a la llamada a session()->increment al inicio de este método
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
