<?php

use App\Http\Middleware\ActiveAccountMiddleware;
use App\Http\Middleware\CheckIfActive;
use App\Http\Middleware\SetLocalLanguage;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

$app = Application::configure(basePath: dirname(__DIR__))
    // UserEventSubscriber is registered in AppServiceProvider, as
    // EventServiceProvider did. Discovery would register it a second time.
    ->withEvents(discover: false)
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        // Same groups, in the same order, as the Laravel 8 RouteServiceProvider:
        // the four panels first, then api and web.
        using: function () {
            foreach (['manager', 'school', 'supervisor', 'teacher'] as $panel) {
                Route::middleware(['web', $panel, "auth:$panel", 'checkIfActive', 'local', "session.password:$panel", "force.password:$panel"])
                    ->prefix($panel)
                    ->as("$panel.")
                    ->namespace('App\Http\Controllers')
                    ->group(base_path("routes/$panel.php"));
            }

            Route::prefix('api')
                ->middleware('api')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/api.php'));

            Route::middleware(['web', 'local'])
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/web.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trimStrings(except: ['old_password']);

        // The Laravel 8 api group (the web group is the framework's, as it was)
        $middleware->group('api', [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // RouteServiceProvider::HOME of Laravel 8, for the "guest" middleware
        $middleware->redirectUsersTo('/home');

        $middleware->alias([
            'supervisor' => \App\Http\Middleware\RedirectIfNotSupervisor::class,
            'supervisor.guest' => \App\Http\Middleware\RedirectIfSupervisor::class,
            'teacher' => \App\Http\Middleware\RedirectIfNotTeacher::class,
            'teacher.guest' => \App\Http\Middleware\RedirectIfTeacher::class,
            'school' => \App\Http\Middleware\RedirectIfNotSchool::class,
            'school.guest' => \App\Http\Middleware\RedirectIfSchool::class,
            'manager' => \App\Http\Middleware\RedirectIfNotManager::class,
            'manager.guest' => \App\Http\Middleware\RedirectIfManager::class,
            'auth' => \App\Http\Middleware\Authenticate::class,
            'activeAccount' => ActiveAccountMiddleware::class,
            'local' => SetLocalLanguage::class,
            'checkIfActive' => CheckIfActive::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'share_user_data' => \App\Http\Middleware\ShareUserDataToViewsMiddleware::class,
            'force.password' => \App\Http\Middleware\ForcePasswordChange::class,
            'session.password' => \App\Http\Middleware\ValidateSessionPassword::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('login-sessions:prune --days=365')->dailyAt('03:00');
        $schedule->command('user:check-expire')->twiceDaily();
    })
    ->create();

// The Laravel 8 exception handler is kept as it is: it reports to Telegram,
// shapes the JSON errors and the per-guard login redirects.
$app->singleton(ExceptionHandler::class, \App\Exceptions\Handler::class);

return $app;
