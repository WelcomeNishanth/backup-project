<?php

namespace Bootstrap;

use Monolog\Logger as Logger;
use Illuminate\Log\Writer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Bootstrap\ConfigureLogging as BaseConfigureLogging;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class ConfigureLogging extends BaseConfigureLogging {

    /**
     * OVERRIDE PARENT
     * Configure the Monolog handlers for the application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Log\Writer  $log
     * @return void
     */
    protected function configureHandlers(Application $app, Writer $log) {
        // Stream handlers
        // need to handle our customized logs

        $bubble = false;

        $serverAddress = 'localhost';
        if (array_key_exists('SERVER_ADDR', $_SERVER)) {
            $serverAddress = $_SERVER['SERVER_ADDR'];
        }

        $format = "[%datetime%] " . " " . $serverAddress . " " . getenv('HOSTNAME') . " " . getenv('APP_NAME') . " " . getenv('APP_ENV')
                . " " . $_REQUEST['requestId'] . " : %message% \n";

        // Stream Handlers
        $infoWarningStreamHandler = new StreamHandler('php://stdout', $this->getLogLevel(), $bubble);
        $infoWarningStreamHandler->setFormatter(new LineFormatter($format, null, true, true));
        $errorStreamHandler = new StreamHandler('php://stderr', $this->getLogLevel(), $bubble);
        $errorStreamHandler->setFormatter(new LineFormatter($format, null, true, true));

        // Get monolog instance and push handlers
        $monolog = $log->getMonolog();
        $monolog->pushHandler($infoWarningStreamHandler);
        $monolog->pushHandler($errorStreamHandler);
    }

    /*
     * getLogLevel
     */

    private function getLogLevel() {
        $logLevel = getenv("APP_LOG_LEVEL");

        if (!empty($logLevel)) {
            $logLevel = strtolower($logLevel);
        } else {
            $logLevel = 'error';
        }

        switch ($logLevel) {
            case 'debug': return Logger::DEBUG;
            case 'info': return Logger::INFO;
            case 'notice': return Logger::NOTICE;
            case 'warning': return Logger::WARNING;
            case 'error': return Logger::ERROR;
            case 'critical': return Logger::CRITICAL;
            case 'alert': return Logger::ALERT;
            case 'emergency': return Logger::EMERGENCY;
        }
    }

}
