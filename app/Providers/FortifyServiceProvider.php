<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponse::class, function () {
            return new class implements LoginResponse {
                public function toResponse($request)
                {
                    $user = $request->user();

                    $url = $user && $user->isAdmin()
                        ? route('admin.dashboard')
                        : route('dashboard');

                    return $request->wantsJson()
                        ? response()->json(['redirect' => $url])
                        : redirect()->intended($url);
                }
            };
        });

        $this->app->singleton(TwoFactorLoginResponse::class, function () {
            return new class implements TwoFactorLoginResponse {
                public function toResponse($request)
                {
                    $user = $request->user();

                    $url = $user && $user->isAdmin()
                        ? route('admin.dashboard')
                        : route('dashboard');

                    return $request->wantsJson()
                        ? response()->json(['redirect' => $url])
                        : redirect()->intended($url);
                }
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        Fortify::loginView(fn () => view('auth.login'));

        Fortify::registerView(fn () => view('auth.register'));

        Fortify::requestPasswordResetLinkView(fn () => view('auth.forgot-password'));

        Fortify::resetPasswordView(fn ($request) => view('auth.reset-password', [
            'request' => $request,
        ]));

        Fortify::verifyEmailView(fn () => view('auth.verify-email'));

        Fortify::confirmPasswordView(fn () => view('auth.confirm-password'));

        Fortify::twoFactorChallengeView(fn () => view('auth.two-factor-challenge'));

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Vérifiez votre adresse email')
                ->markdown('emails.auth.verify-email', [
                    'user' => $notifiable,
                    'url' => $url,
                    'appName' => config('app.name'),
                    'expire' => config('auth.verification.expire', 60),
                ]);
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('passkeys', function (Request $request) {
            $credentialId = $request->input('credential.id');

            return Limit::perMinute(10)->by(
                ($credentialId ?: $request->session()->getId()).'|'.$request->ip()
            );
        });
    }
}
