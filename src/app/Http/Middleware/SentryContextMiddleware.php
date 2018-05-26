<?php

namespace App\Http\Middleware;

use Closure;

class SentryContextMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (app()->bound('sentry')) {
            $serverAddress = 'localhost';
            if (array_key_exists('SERVER_ADDR', $_SERVER)) {
                $serverAddress = $_SERVER['SERVER_ADDR'];
            }

            /** @var \Raven_Client $sentry */
            $sentry = app('sentry');
            // Add tags context
            $sentry->tags_context(array(
                'environment' => getenv('APP_ENV'),
                'hostname' => getenv('HOSTNAME'),
                'request_id' => $_REQUEST['requestId'],
                'server_address' => $serverAddress
            ));
        }

        return $next($request);
    }

}
