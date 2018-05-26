<?php

namespace App\Services;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Log;

class MiddlewareFactory {

    /**
     * @access private
     *
     * @param bool $delay default to true, can be false to speed up tests
     *
     * @return callable
     */
    public function retry($delay = true) {
        if ($delay) {
            return Middleware::retry($this->newRetryDecider(), $this->getRetryDelay());
        } else {
            return Middleware::retry($this->newRetryDecider());
        }
    }

    /**
     * Returns a method that takes the number of retries and returns the number of milliseconds
     * to wait
     *
     * @return callable
     */
    private function getRetryDelay() {
        return function( $numberOfRetries ) {
            return 1000 * $numberOfRetries;
        };
    }

    /**
     * @return callable
     */
    private function newRetryDecider() {
        return function (
                $retries,
                Request $request,
                Response $response = null,
                RequestException $exception = null
                ) {
            // Don't retry if we have run out of retries
            if ($retries >= 3) {
                return false;
            }

            $shouldRetry = false;

            // Retry connection exceptions
            if ($exception instanceof ConnectException) {
                $shouldRetry = true;
            }

            if ($response) {
                // Retry on server errors
                if ($response->getStatusCode() >= 500) {
                    $shouldRetry = true;
                }
            }

            // Log if we are retrying
            if ($shouldRetry) {
                Log::info(
                        sprintf(
                                'Retrying %s %s %s/5, %s', $request->getMethod(), $request->getUri(), $retries + 1, $response ? 'status code: ' . $response->getStatusCode() :
                                $exception->getMessage()
                ));
            }

            return $shouldRetry;
        };
    }

}
