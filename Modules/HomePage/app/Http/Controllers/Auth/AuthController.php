<?php

namespace Modules\HomePage\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('homepage::auth.login');
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        return view('homepage::auth.register');
    }

    /**
     * Show the forgot password form.
     */
    public function showForgotPassword()
    {
        return view('homepage::auth.forgot-password');
    }

    /**
     * Show the reset password form.
     */
    public function showResetPassword(Request $request, $token)
    {
        return view('homepage::auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    /**
     * Handle authentication attempt.
     */
    public function login(Request $request)
    {
        $loginField = $request->input('login_type') === 'cpf' ? 'cpf' : 'email';

        $credentials = $request->validate([
            $loginField => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->hasRole('admin')) {
                return redirect()->route('admin.index');
            } elseif ($user->hasRole('support')) {
                return redirect()->route('support.index');
            } elseif ($user->isPro()) {
                return redirect()->route('core.dashboard');
            } else {
                return redirect()->route('paneluser.index');
            }
        }

        return back()->withErrors([
            $loginField => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ])->onlyInput($loginField, 'remember');
    }

    /**
     * Handle registration attempt.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'cpf' => ['nullable', 'string', 'max:14', 'unique:users'],
            'birth_date' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user = \App\Models\User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'cpf' => $validated['cpf'],
            'birth_date' => $validated['birth_date'],
            'phone' => $validated['phone'],
        ]);

        Auth::login($user);

        return redirect()->intended('/');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
