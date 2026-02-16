<?php

declare(strict_types=1);

namespace Modules\HomePage\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Modules\Core\Services\RecaptchaService;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('homepage::auth.login', [
            'recaptchaEnabled' => recaptcha_enabled(),
            'recaptchaSiteKey' => recaptcha_site_key(),
        ]);
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        return view('homepage::auth.register', [
            'recaptchaEnabled' => recaptcha_enabled(),
            'recaptchaSiteKey' => recaptcha_site_key(),
        ]);
    }

    /**
     * Show the forgot password form.
     */
    public function showForgotPassword()
    {
        return view('homepage::auth.forgot-password');
    }

    /**
     * Handle forgot password request.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Enviamos um link de recuperação para o seu e-mail.');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show the reset password form.
     */
    public function showResetPassword(Request $request, $token)
    {
        return view('homepage::auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    /**
     * Handle reset password request.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Sua senha foi alterada. Faça login com a nova senha.');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    /**
     * Handle authentication attempt.
     */
    public function login(Request $request)
    {
        $recaptcha = app(RecaptchaService::class);
        if ($recaptcha->isEnabled()) {
            $request->validate(['g-recaptcha-response' => ['required', 'string']]);
            if (! $recaptcha->verify($request->input('g-recaptcha-response'), 'login')) {
                return back()->withErrors(['g-recaptcha-response' => 'Verificação de segurança falhou. Tente novamente.'])
                    ->onlyInput('email', 'remember');
            }
        }

        $loginField = $request->input('login_type') === 'cpf' ? 'cpf' : 'email';

        $credentials = $request->validate([
            $loginField => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $ip = $request->ip();
            Cache::forget('login_attempts_' . $ip);
            Cache::forget('login_lockout_' . $ip);

            $request->session()->regenerate();

            $user = Auth::user();

            if (setting('security_single_session', false)
                && config('session.driver') === 'database'
                && \Illuminate\Support\Facades\Schema::hasTable('sessions')) {
                $currentSessionId = $request->session()->getId();
                DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->where('id', '!=', $currentSessionId)
                    ->delete();
            }

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

        $ip = $request->ip();
        $attemptsKey = 'login_attempts_' . $ip;
        $lockoutKey = 'login_lockout_' . $ip;

        $maxAttempts = (int) setting('security_login_max_attempts', setting('max_login_attempts', 5));
        $lockoutMinutes = (int) setting('security_lockout_time', 15);

        $attempts = (int) Cache::get($attemptsKey, 0) + 1;
        Cache::put($attemptsKey, $attempts, $lockoutMinutes * 60 + 3600);

        if ($attempts >= $maxAttempts) {
            Cache::put($lockoutKey, true, $lockoutMinutes * 60);
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
        $recaptcha = app(RecaptchaService::class);
        if ($recaptcha->isEnabled()) {
            $request->validate(['g-recaptcha-response' => ['required', 'string']]);
            if (! $recaptcha->verify($request->input('g-recaptcha-response'), 'register')) {
                return back()->withErrors(['g-recaptcha-response' => 'Verificação de segurança falhou. Tente novamente.']);
            }
        }

        $request->merge([
            'cpf' => lgpd_clean_cpf($request->cpf ?? null) ?: null,
            'phone' => lgpd_clean_phone($request->phone ?? null) ?: null,
            'birth_date' => parse_brl_date($request->birth_date ?? null) ?? $request->birth_date,
        ]);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
            'cpf' => ['nullable', 'string', 'size:11', 'unique:users'],
            'birth_date' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:15'],
        ]);

        $user = \App\Models\User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'cpf' => $validated['cpf'],
            'birth_date' => $validated['birth_date'],
            'phone' => $validated['phone'],
        ]);

        \Illuminate\Support\Facades\Mail::to($user->email)->queue(new \App\Mail\WelcomeEmail($user));

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
