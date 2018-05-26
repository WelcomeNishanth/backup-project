<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Routing\Router;
use Illuminate\Contracts\Foundation\Application;

class Kernel extends HttpKernel {

    /**
     * Constructor
     */
    public function __construct(Application $app, Router $router) {
        parent::__construct($app, $router);

        foreach ($this->bootstrappers as $key => $boostrapper) {
            if ($boostrapper === 'Illuminate\Foundation\Bootstrap\ConfigureLogging') {
                unset($this->bootstrappers[$key]);
            }
        }
        $this->bootstrappers[] = 'Bootstrap\ConfigureLogging';
    }

    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\SentryContextMiddleware::class,
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
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
        'api' => [
            'bindings',
            \App\Http\Middleware\AfterMiddleware::class,
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
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'auth0.jwt' => \Auth0\Login\Middleware\Auth0JWTMiddleware::class,
        'auth0.jwt_verification' => \Auth0\Login\Middleware\Auth0OptionalJWTMiddleware::class,
        'auth0.jwt_force' => \Auth0\Login\Middleware\ForceAuthMiddleware::class,
	'authtoken' => \App\Http\Middleware\AuthUserTokenMiddleware::class,
        'auth0API' => \App\Http\Middleware\Auth0ApiTokenMiddleware::class,
        'auth.apikey' => \App\Http\Middleware\AzureApiKeyMiddleware::class
    ];
}