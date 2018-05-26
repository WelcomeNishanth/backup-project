<?php

/*
  |--------------------------------------------------------------------------
  | Detect The Application Environment
  |--------------------------------------------------------------------------
  |
  | Laravel takes a dead simple approach to your application environments
  | so you can just specify a machine name for the host that matches a
  | given environment, then we will automatically detect it for you.
  |
 */

use App\Common\AwsTools;
use App\Common\Util;

$env = $app->detectEnvironment(function() {
    $app_env = $_ENV['ENVIRONMENT'];

    if (!empty($app_env)) {
        $app_env_file = '.' . $app_env . '.env';

        $homeDir = __DIR__ . '/../';
        putenv("home.dir=$homeDir");

        $environmentPath = $homeDir . $app_env_file;

        $lastModified = 0;
        if (file_exists($environmentPath)) {
            $lastModified = filemtime($environmentPath);
        }

        $s3Client = AwsTools::getS3Client('us-west-2');
        $envS3Key = 'gateway-portal/ui/' . $app_env . '/' . $app_env_file;

        $envLastModifiedinS3 = 0;
        try {
            $envLastModifiedinS3 = AwsTools::getLastModified($s3Client, 'bd-auth-keys', $envS3Key);
        } catch (\Exception $exception) {
            // Ignore if this S3 call fails to connect to instance profile metadata server
        }

        if ($lastModified == 0 || $lastModified < $envLastModifiedinS3) {
            $fileContent = AwsTools::getObjectFromS3($s3Client, 'bd-auth-keys', $envS3Key);
            Util::putFileContent($environmentPath, $fileContent);
        }

        $dotenv = new Dotenv\Dotenv(__DIR__ . '/../', $app_env_file);
        $dotenv->overload();
    }
});
