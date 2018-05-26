<?php

namespace App\Services;

use App\Services\Utils;
use Azure\Client\Configuration;
use Azure\Client\ApiClient;
use Azure\Client\Api\UsersApi;
use Azure\Client\Model\Subscription;
use Azure\Client\Model\User;
use Azure\Client\ApiException;

class AzureService {

    private static function getSharedAccessToken() {
        $id = getenv("AZURE_API_MANAGEMENT_ID");
        $key = getenv("AZURE_API_MANAGEMENT_KEY");

        $expiry = strtotime("+10 minutes");
        $expiry_in_iso_format = Utils::converToISOTime($expiry);

        $algo = getenv('SHA_ALG');

        $array = array($id, $expiry_in_iso_format);
        $data = \implode("\n", $array);

        $hash = hash_hmac($algo, $data, $key, true);
        $encodedHash = \base64_encode($hash);

        $sas = 'SharedAccessSignature uid=' . $id . '&ex=' . $expiry_in_iso_format . '&sn=' . $encodedHash;

        return $sas;
    }

    private static function getAzureApiClient() {
        $config = new Configuration();
        $api_uri = getenv("AZURE_API_MANAGEMENT_URL");

        $config->setHost($api_uri);
        $config->setApiKey('Authorization', self::getSharedAccessToken());

        $apiClient = new ApiClient($config);
        $apiEndpoint = new UsersApi($apiClient);

        return $apiEndpoint;
    }

    public static function createDeveloperUserAndGetApiKey($userDetails) {
        $apiKey = null;

        $api_version = getenv("AZURE_API_VERSION");
        $user_id = \uniqid("", false);

        $user = new User();
        $user->setFirstName($userDetails['firstName']);
        $user->setLastName($userDetails['lastName']);
        $user->setEmail($userDetails['email']);
        $user->setState(getenv("AZURE_ACTIVE_STATE"));

        $apiEndpoint = self::getAzureApiClient();
        $createResponse = $apiEndpoint->userCreate($user_id, $api_version, $user);
        if (isset($createResponse->id) && !is_null($createResponse->id)) {
            $subscription_id = \uniqid("", false);

            $subscription = new Subscription();
            $subscription->setUserId($createResponse->id);
            $subscription->setProductId(getenv("AZURE_API_MANAGEMENT_GW_API_PID"));
            $subscription->setName(getenv("AZURE_GATEWAY_SUBSCRIPTION_NAME"));
            $subscription->setState(getenv("AZURE_ACTIVE_STATE"));

            $subscriptionResponse = $apiEndpoint->userCreateSubscription($subscription_id, $api_version, $subscription);
            if (isset($subscriptionResponse->id) && !is_null($subscriptionResponse->id)) {
                $apiKey = $subscriptionResponse->primaryKey;
            }
        } 

        return array("api_key" => $apiKey, "az_user_id" => $user_id, "az_subscription_id" => $subscription_id);
    }

    public static function regenerateApiKey($subscriptionId) {
        $apiKey = null;
        $api_version = getenv("AZURE_API_VERSION");

        $apiEndpoint = self::getAzureApiClient();
        try {
            $apiEndpoint->userRegeneratePrimaryKey($subscriptionId, $api_version);

            $getResponse = $apiEndpoint->userGetSubscription($subscriptionId, $api_version);
            if (isset($getResponse->id) && !is_null($getResponse->id)) {
                $apiKey = $getResponse->primaryKey;
            }
        } catch (ApiException $exception) {
            throw $exception;
        }

        return $apiKey;
    }

}
