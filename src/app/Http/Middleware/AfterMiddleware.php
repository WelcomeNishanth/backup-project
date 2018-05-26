<?php

namespace App\Http\Middleware;

use Closure;

class AfterMiddleware {

    public function handle($request, Closure $next) {
        $response = $next($request);

        $responseStatus = $response->getStatusCode();
	
        if ($responseStatus != 200 && $responseStatus != 400 && $responseStatus != 500) {
            $responseOut = array(
                'statusCode' => $responseStatus,
                'success' => false,
                'message' => $response->content(),
                'devMsg' => $response->content()
            );

            $response->setContent(json_encode($responseOut));
        } 

	if(strpos($response->headers->get('content-type'), 'pdf') === false) {
	     $response->header('Content-Type', 'application/json');
	}

        return $response;
    }

}
