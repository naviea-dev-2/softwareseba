<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    // ->withRouting(
    //     web: __DIR__.'/../routes/web.php',
    //     commands: __DIR__.'/../routes/console.php',
    //     health: '/up',
    // )
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        using: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
            Route::middleware('web')
                ->group(base_path('routes/hr.php'));
            Route::middleware('web')
                ->group(base_path('routes/account.php'));
            Route::middleware('web')
                ->group(base_path('routes/inventory.php'));
            Route::middleware('web')
                ->group(base_path('routes/report.php'));
            Route::middleware('web')
                ->group(base_path('routes/admin_talika.php'));
            Route::middleware('web')
                ->group(base_path('routes/admin_inventory.php'));
            Route::middleware('web')
                ->group(base_path('routes/user.php'));
            Route::middleware('web')
                ->group(base_path('routes/crm.php'));
            Route::middleware('web')
                ->group(base_path('routes/dealer.php'));
        },

    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'iclock/*',
        ]);
        // $middleware->group('web', [
        //     \Illuminate\Cookie\Middleware\EncryptCookies::class,
        //     \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        //     \Illuminate\Session\Middleware\StartSession::class,
        //     \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        //     \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        //     \Illuminate\Routing\Middleware\SubstituteBindings::class,
        // ]);
        // $middleware->appendToGroup('api', [
        //     \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
        //     \Illuminate\Routing\Middleware\SubstituteBindings::class,
        // ]);

        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'signed' => \App\Http\Middleware\ValidateSignature::class,
            'is_active' => \App\Http\Middleware\IsUserActive::class,
            'permission' => \App\Http\Middleware\Permission::class,
        ]);
        // $middleware->validateCsrfTokens(except: [
        //     'iclock/*',
        // ]);
       
        // $middleware->use([
        //     // \Illuminate\Http\Middleware\TrustHosts::class,
        //     \Illuminate\Http\Middleware\TrustProxies::class,
        //     \Illuminate\Http\Middleware\HandleCors::class,
        //     \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
        //     \Illuminate\Http\Middleware\ValidatePostSize::class,
        //     \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
        //     \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        // ]);
        // $middleware->appendToGroup('web', [
        //     \App\Http\Middleware\EncryptCookies::class,
        //     \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        //     \Illuminate\Session\Middleware\StartSession::class,
        //     \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        //     \App\Http\Middleware\VerifyCsrfToken::class,
        //     \Illuminate\Routing\Middleware\SubstituteBindings::class,
        // // ]);
        // $middleware->group('web', [
        //     \Illuminate\Cookie\Middleware\EncryptCookies::class,
        //     \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        //     \Illuminate\Session\Middleware\StartSession::class,
        //     \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        //     \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        //     \Illuminate\Routing\Middleware\SubstituteBindings::class,
        // ]);
        // $middleware->appendToGroup('api', [
        //     // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        //     \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
        //     \Illuminate\Routing\Middleware\SubstituteBindings::class,
        // ]);
        //  $middleware->alias([
        //     'agent' => \App\Http\Middleware\AgentCheck::class,
        //     'admin' => \App\Http\Middleware\AdminCheck::class,
        //     'school' => \App\Http\Middleware\SchoolCheck::class,
        //     'student' => \App\Http\Middleware\StudentCheck::class,
        //     'guardian' => \App\Http\Middleware\GuardianCheck::class,
        //     'teacher' => \App\Http\Middleware\TeacherCheck::class,
        //     'redirectIfAuthenticated' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        // ]);
        // $middleware->alias([
        //     'auth' => \App\Http\Middleware\Authenticate::class,
        //     // 'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        //     // 'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        //     // 'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        //     // 'can' => \Illuminate\Auth\Middleware\Authorize::class,
        //     'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        //     // 'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        //     // 'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        //     'signed' => \App\Http\Middleware\ValidateSignature::class,
        //     // 'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        //     // 'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        //     'is_active' => \App\Http\Middleware\IsUserActive::class,
        //     'permission' => \App\Http\Middleware\Permission::class
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
