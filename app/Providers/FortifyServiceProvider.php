<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Login View
        Fortify::loginView(function () {
            return Inertia::render('auth/Login', [
                'status' => session('status'),
            ]);
        });

        // Registration View
        Fortify::registerView(function () {
            return Inertia::render('auth/Register', [
                'status' => session('status'),
            ]);
        });

        // Forgot Password View
        Fortify::requestPasswordResetLinkView(function () {
            return Inertia::render('auth/ForgotPassword', [
                'status' => session('status'),
            ]);
        });

        // Reset Password View
        Fortify::resetPasswordView(function (Request $request) {
            return Inertia::render('auth/ResetPassword', [
                'email' => $request->email,
                'token' => $request->route('token'),
            ]);
        });

        // Email Verification View
        Fortify::verifyEmailView(function () {
            return Inertia::render('auth/VerifyEmail', [
                'status' => session('status'),
            ]);
        });

        // Confirm Password View
        Fortify::confirmPasswordView(function () {
            return Inertia::render('auth/ConfirmPassword');
        });

        // Two Factor Challenge View
        Fortify::twoFactorChallengeView(function () {
            return Inertia::render('auth/TwoFactorChallenge');
        });

        // Rate Limiting
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(5)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}