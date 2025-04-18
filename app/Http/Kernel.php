<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'utils.determine-request-data-owner' => \App\Http\Middleware\Utils\DetermineRequestDataOwner::class,
        'company.ensure-user-dont-have-company' => \App\Http\Middleware\Company\EnsureUserDontHaveCompany::class,
        'project.ensure-project-belonging' => \App\Http\Middleware\Project\EnsureProjectBelongingMiddleware::class,
        'custom-ahp.protect-default-model' => \App\Http\Middleware\CustomAhp\ProtectDefaultModelMiddleware::class,
        'custom-item-price.protect-default-model' => \App\Http\Middleware\CustomItemPrice\ProtectDefaultModelMiddleware::class,
        'request.strip-empty-char-on-request' => \App\Http\Middleware\Request\StripEmptyCharOnRequestMiddleware::class,
        'project.ensure-project-eligible-to-export' => \App\Http\Middleware\Project\EnsureProjectEligibleToExportMiddleware::class,
        'protect-debug' => \App\Http\Middleware\ProtectDebugMiddleware::class,
        'ensure.demo.eligibility' => \App\Http\Middleware\Project\EnsureDemoEligibilityMiddleware::class,
        'project.subscription.limitation.guard' => \App\Http\Middleware\Project\ProjectSubscriptionLimitationGuard::class,
    ];
}
