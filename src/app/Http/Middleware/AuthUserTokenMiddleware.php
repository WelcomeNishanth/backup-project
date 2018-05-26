<?php

namespace App\Http\Middleware;

use Auth0\SDK\API\Authentication;
use Closure;
use App\Services\Utils;
use Illuminate\Support\Facades\Log;

class AuthUserTokenMiddleware {

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

        $api = new Authentication(config('laravel-auth0.domain'), config('laravel-auth0.client_id'), null, Utils::getGuzzleOptions());
        $tokeninfo = $api->tokeninfo($token);

        if (Utils::check_keys_in_array($tokeninfo, ['gw_user_id', 'gw_company_id']) && !empty($tokeninfo['gw_user_id']) && !empty($tokeninfo['gw_company_id'])) {
            $_REQUEST['AUTHUSERDATA'] = $tokeninfo;
            $_REQUEST['TOKEN'] = $token;

            return $next($request);
        } else {
            $errorMessage = trans('messages.user_company_id_missing');
            Log::error($errorMessage);

            http_response_code(400);
            $out = array('statusCode' => http_response_code(), 'success' => false, 'message' => $errorMessage);

            return response()->json($out, 400);
        }
    }

}
