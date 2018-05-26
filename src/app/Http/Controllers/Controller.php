<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Exception;

class Controller extends BaseController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    public function logErrorAndSendToSentry(Exception $exception, $errorMessage = NULL, $devMsg = NULL) {
        $message = '';
        if (!empty($errorMessage)) {
            $message = $errorMessage;
        }
        Log::error($message . $exception->getMessage() . ":" . $exception->getTraceAsString());

        $extraData = array();
        if (!empty($devMsg)) {
            $extraData['extra'] = array('devMsg' => $devMsg);
        }
        app('sentry')->captureException($exception, $extraData);
    }

    public function logErrorAndSendResponse($errorMessage, $devMsg = NULL, $toLog = true, $http_code = 400) {
        if ($toLog) {
            Log::error($errorMessage);
        }

        $responseOut = array(
            'statusCode' => $http_code,
            'success' => false,
            'message' => $errorMessage);

        if (!empty($devMsg)) {
            $responseOut['devMsg'] = $devMsg;
        }

        return $this->sendJsonResponse($responseOut, $http_code);
    }

    public function logExceptionAndSendResponse(Exception $exception, $errorMessage = '', $devMsg = NULL, $toLog = true, $http_code = 500) {
        $devErrorMessage = $devMsg;
        if (empty($devErrorMessage)) {
            $devErrorMessage = $exception->getMessage() . ":" . $exception->getTraceAsString();
        }

        if ($toLog) {
            Log::error($errorMessage . ":" . $devErrorMessage);
        }

        $extraData = array();
        if (!empty($devMsg)) {
            $extraData['extra'] = array('devMsg' => $devMsg);
        }
        app('sentry')->captureException($exception, $extraData);

        $responseOut = array(
            'statusCode' => $http_code,
            'success' => false,
            'message' => $errorMessage,
            'devMsg' => $devErrorMessage);

        return $this->sendJsonResponse($responseOut, $http_code);
    }

    public function sendSuccessResponse($data, $message = '', $http_code = 200) {
        $responseOut = array(
            'statusCode' => $http_code,
            'success' => true
        );

        if (!empty($data)) {
            $responseOut['data'] = $data;
        }
        if (!empty($message)) {
            $responseOut['message'] = $message;
        }

        return $this->sendJsonResponse($responseOut, $http_code);
    }

    public function sendJsonResponse($jsonContent, $http_code) {
        http_response_code($http_code);

        return response()->json($jsonContent, $http_code);
    }

}
