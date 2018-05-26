<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Middleware;

use Auth0\SDK\JWTVerifier;
use Closure;
use App\Services\Utils;

class Auth0ApiTokenMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $token = $request->header('Authorization');
        $token = str_replace('Bearer ', '', $token);

        $verifier = new JWTVerifier([
            'valid_audiences' => [config('laravel-auth0.admin_api_identifier')],
            'client_secret' => config('laravel-auth0.admin_client_secret'),
            'guzzle_options' => Utils::getGuzzleOptions()
        ]);

        $verifier->verifyAndDecode($token);

        return $next($request);
    }

}
