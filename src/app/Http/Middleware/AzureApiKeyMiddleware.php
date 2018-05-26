<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\UserService;
use App\Services\Utils;
use Illuminate\Support\Facades\Log;

class AzureApiKeyMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $apiKey = $request->header('Authorization');

        $userDetails = UserService::getUserByApiKey($apiKey);
        if (!empty($userDetails)) {
            if (strcmp($userDetails['status'], 'inactive') == 0 ||
                    strcmp($userDetails['company_status'], 'inactive') == 0 || !Utils::validateBooleanField($userDetails['is_activated'])) {
                $errorMessage = trans('messages.api_key_not_active');
                Log::error($errorMessage);

                http_response_code(401);
                $out = array('statusCode' => http_response_code(), 'success' => false, 'message' => $errorMessage);

                return response()->json($out, 401);
            } else {
                $_REQUEST['AUTHUSERDATA'] = array(
                    "gw_user_id" => $userDetails['user_id'],
                    "gw_company_id" => $userDetails['company_id'],
                    "name" => Utils::validateStringField($userDetails['first_name']) ? $userDetails['first_name'] : $userDetails['last_name'],
                    "email" => $userDetails["email"],
                    "roles" => array("gw_user")
                );

                return $next($request);
            }
        } else {
            $errorMessage = trans('messages.api_key_not_found');
            Log::error($errorMessage);

            http_response_code(401);
            $out = array('statusCode' => http_response_code(), 'success' => false, 'message' => $errorMessage);

            return response()->json($out, 401);
        }
    }

}
