<?php

namespace App\Common;

/**
 * This contains all the constants for the AWS SDK.
 *
 * @author Ajay Garga
 *
 */
use App\Common\AppConstants;
use App\Common\AwsConstants;
use Aws\Credentials\Credentials;
use Aws\S3\S3Client;
use Aws\Sns\SnsClient;

class AwsTools {

    public static function getS3Client($region) {
        $accessKeyId = getenv(AppConstants::ENV_ACCESS_KEY);
        if (!$accessKeyId) {
            $accessKeyId = null;
        }

        $secretAccessKey = getenv(AppConstants::ENV_SECRET_KEY);
        if (!$secretAccessKey) {
            $secretAccessKey = null;
        }

        $params = array(
            AwsConstants::VALIDATION => false,
            AwsConstants::SCHEME => AwsConstants::HTTP_SCHEME
        );

        $params [AwsConstants::REGION] = $region;
        $params[AwsConstants::VERSION] = AwsConstants::LATEST;

        if (!empty($accessKeyId) && !empty($secretAccessKey)) {
            $credentials = new Credentials($accessKeyId, $secretAccessKey);
            $params [AwsConstants::CREDENTIALS] = $credentials;
        }

        $s3Client = S3Client::factory($params);

        return $s3Client;
    }

    public static function getObjectFromS3($s3Client, $bucket, $objectKey) {
        $response = $s3Client->getObject(array(
            AwsConstants::BUCKET => $bucket,
            AwsConstants::KEY => $objectKey));

        $response[AwsConstants::BODY]->rewind();

        return (string) $response[AwsConstants::BODY];
    }

    public static function getLastModified($s3Client, $bucket, $objectKey) {
        $response = $s3Client->headObject(array(
            AwsConstants::BUCKET => $bucket,
            AwsConstants::KEY => $objectKey));

        $lastModifiedDate = $response[AwsConstants::LASTMODIFIED];

        return $lastModifiedDate->getTimestamp();
    }

    public static function getSnsClient($region) {
        $accessKeyId = getenv(AppConstants::ENV_ACCESS_KEY);
        if (!$accessKeyId) {
            $accessKeyId = null;
        }

        $secretAccessKey = getenv(AppConstants::ENV_SECRET_KEY);
        if (!$secretAccessKey) {
            $secretAccessKey = null;
        }

        $params = array(
            AwsConstants::REGION => $region,
            AwsConstants::VERSION => AwsConstants::LATEST
        );

        if (!empty($accessKeyId) && !empty($secretAccessKey)) {
            $credentials = new Credentials($accessKeyId, $secretAccessKey);
            $params [AwsConstants::CREDENTIALS] = $credentials;
        }

        $snsClient = SnsClient::factory($params);

        return $snsClient;
    }

    public static function sendMessage($snsClient, $queueOrTopicName, $subject, $message, $messageContentType = null, array $properties = null, array $additionalInfo = null) {
        try {
            $result = $snsClient->publish(
                    array(
                        AwsConstants::TOPIC_ARN => $queueOrTopicName,
                        // Message is required
                        AwsConstants::MESSAGE => $message,
                        AwsConstants::SUBJECT => $subject,
                        AwsConstants::MESSAGE_ATTRIBUTES => $properties
            ));
        } catch (ServiceResponseException $e) {
            throw new \Exception($e);
        }
    }

    public static function getMessageProperties($objectClass, $objectId, $objectKey, $action = AppConstants::ACTION_UPDATE, $modifiedDate = null, $source = AppConstants::GATEWAY) {
        $properties [AppConstants::ID] = array(
            AppConstants::DATA_TYPE => AppConstants::STRING,
            AppConstants::STRING_VALUE => $objectId
        );
        $properties [AppConstants::MSGKEY] = array(
            AppConstants::DATA_TYPE => AppConstants::STRING,
            AppConstants::STRING_VALUE => $objectKey
        );

        $messageType = $objectClass;
        $properties [AppConstants::KIND] = array(
            AppConstants::DATA_TYPE => AppConstants::STRING,
            AppConstants::STRING_VALUE => $messageType
        );

        $properties [AppConstants::ACTION] = array(
            AppConstants::DATA_TYPE => AppConstants::STRING,
            AppConstants::STRING_VALUE => $action
        );

        $properties [AppConstants::SOURCE] = array(
            AppConstants::DATA_TYPE => AppConstants::STRING,
            AppConstants::STRING_VALUE => $source
        );

        $properties [AppConstants::LAST_MODIFIED] = array(
            AppConstants::DATA_TYPE => AppConstants::STRING,
            AppConstants::STRING_VALUE => $modifiedDate
        );

        return $properties;
    }

}
