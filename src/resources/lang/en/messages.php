<?php

use App\Common\AwsTools;
use App\Common\Util;

$app_env = $_ENV['ENVIRONMENT'];

if (!empty($app_env)) {
    $app_env_file = 'messages.ini';
    $home_dir = getenv("home.dir");

    $messagesPath = $home_dir . "/" . $app_env_file;

    $lastModified = 0;
    if (file_exists($messagesPath)) {
        $lastModified = filemtime($messagesPath);
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
        Util::putFileContent($messagesPath, $fileContent);
    }

    return (parse_ini_file($messagesPath, true));
}

