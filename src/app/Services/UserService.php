<?php

namespace App\Services;

use App\Notification\EmailNotifier;
use Illuminate\Support\Facades\DB;
use \PDO;
use \App\Common\AwsTools;
use App\Services\Utils;
use Auth0\SDK\API\Authentication;
use Auth0\SDK\API\Management;
use Illuminate\Support\Facades\Log;

class UserService {

    public static function sendActivationMailToUsers($accountId, $accountStatus) {
        if (!is_null($accountId) && !is_null($accountStatus) && $accountStatus == 'approved') {
            $expiryDate = strtotime(self::getExpirationInDays());

            DB::setFetchMode(PDO::FETCH_ASSOC);
            $results = DB::select("select user_id, email, first_name from gw_users "
                            . "where company_id = " . $accountId . " and status = 'pending'");
            DB::setFetchMode(PDO::FETCH_CLASS);
            if (count($results) > 0) {
                foreach ($results as $result) {
                    self::sendInvite($result, $expiryDate, true);

                    $userObject = UserService::getUserById($result['user_id']);
                    UserService::sendSns($userObject, 'Update');
                }
            }
        }
    }

    public static function isSuperUser() {
        if (array_key_exists('roles', $_REQUEST['AUTHUSERDATA'])) {
            $roles = $_REQUEST['AUTHUSERDATA']['roles'];
            if (in_array('gw_admin', $roles)) {
                return true;
            }
        }

        return false;
    }

    public static function sendActivationMail($accountStatus, $userDetails) {
        if (!is_null($accountStatus) && $accountStatus == 'approved') {
            $expiryDate = strtotime(self::getExpirationInDays());

            if (strcmp($userDetails['status'], 'pending') == 0) {
                self::sendInvite($userDetails, $expiryDate, true);

                return true;
            }
        }

        return false;
    }

    public static function getExpirationInDays() {
        $expiration_in_days = getenv("EXPIRATION_IN_DAYS");
        if (empty($expiration_in_days)) {
            $expiration_in_days = "+21 day";
        }

        return $expiration_in_days;
    }

    public static function getUserById($userId) {
        $user = DB::table('gw_users')->where('user_id', $userId)->first();

        return $user;
    }

    public static function getApiKeyByUserId($userId) {
        $apiKey = null;

        $results = DB::select("select api_key from gw_users where user_id = " . $userId);

        if (count($results) > 0) {
            $apiKey = $results[0]->api_key;
        }

        return $apiKey;
    }

    public static function getUserByApiKey($api_key) {
        $userDetails = null;

        DB::setFetchMode(PDO::FETCH_ASSOC);
        $results = DB::select("select * from gw_users where api_key = '" . $api_key . "' order by modified_timestamp desc");

        if (count($results) > 0) {
            $userDetails = $results[0];
        }

        DB::setFetchMode(PDO::FETCH_CLASS);

        return $userDetails;
    }

    public static function getActiveUserByEmail($email) {
        DB::setFetchMode(PDO::FETCH_ASSOC);

        $statusCondition = "( NOT ( status = 'inactive' ) )";
        $result = DB::table('gw_users')->where('email', $email)->whereRaw($statusCondition)->orderBy('modified_timestamp', 'desc')->first();

        DB::setFetchMode(PDO::FETCH_CLASS);

        return $result;
    }

    public static function getUserByEmail($email, array $statuses = NULL) {
        DB::setFetchMode(PDO::FETCH_ASSOC);

        if (!empty($statuses)) {
            $statusCondition = "( status = '";
            $index = 0;
            foreach ($statuses as $status) {
                if ($index > 0) {
                    $statusCondition = $statusCondition . " or status = '" . $status . "'";
                } else {
                    $statusCondition = $statusCondition . $status . "'";
                }

                $index++;
            }
            $statusCondition = $statusCondition . " )";

            $results = DB::table('gw_users')->where('email', $email)->whereRaw($statusCondition)->orderBy('modified_timestamp', 'desc')->get();
        } else {
            $results = DB::table('gw_users')->where('email', $email)->orderBy('modified_timestamp', 'desc')->get();
        }

        DB::setFetchMode(PDO::FETCH_CLASS);

        $user = NULL;
        $first = true;
        foreach ($results as $result) {
            if (strcmp($result['company_status'], 'draft') == 0 ||
                    strcmp($result['company_status'], 'pending') == 0) {
                continue;
            }

            if (strcmp($result['status'], 'inactive') != 0 && strcmp($result['company_status'], 'inactive') != 0) {
                $user = $result;
                break;
            } else if ($first) {
                $user = $result;
                $first = false;
            }
        }

        return $user;
    }

    public static function getUserAsArrayById($userId) {
        $userData = NULL;

        DB::setFetchMode(PDO::FETCH_ASSOC);
        $results = DB::select('select * from gw_users where user_id = ' . $userId);
        DB::setFetchMode(PDO::FETCH_CLASS);

        if (count($results) > 0) {
            $userData = $results[0];
        }

        return $userData;
    }

    public static function getUserAsArrayWithCompanyInfo($userId) {
        $userData = NULL;

        DB::setFetchMode(PDO::FETCH_ASSOC);
        $results = DB::select('select first_name, last_name, email, phone_number, gw_users.status, is_activated,
                    primary_user, signed_agreement, name, legal_name from gw_users, gw_companies where user_id = ' . $userId .
                        ' and gw_users.company_id = gw_companies.company_id');
        DB::setFetchMode(PDO::FETCH_CLASS);

        if (count($results) > 0) {
            $userData = $results[0];
        }

        return $userData;
    }

    public static function getUserIdBySfContactId($sfContactId) {
        $user_id = NULL;

        DB::setFetchMode(PDO::FETCH_ASSOC);
        $results = DB::select("select user_id from gw_users where sf_contact_id = '" . $sfContactId . "'");
        DB::setFetchMode(PDO::FETCH_CLASS);

        if (count($results) > 0) {
            $result = $results[0];
            $user_id = $result['user_id'];
        }

        return $user_id;
    }

    public static function getUserIdByCompanyId($company_id, array $statuses = NULL) {
        DB::setFetchMode(PDO::FETCH_ASSOC);

        if (!empty($statuses)) {
            $statusCondition = "( status = '";
            $index = 0;
            foreach ($statuses as $status) {
                if ($index > 0) {
                    $statusCondition = $statusCondition . " or status = '" . $status . "'";
                } else {
                    $statusCondition = $statusCondition . $status . "'";
                }

                $index++;
            }
            $statusCondition = $statusCondition . " )";

            $results = DB::table('gw_users')->where('company_id', $company_id)->whereRaw($statusCondition)
                            ->orderBy('creation_timestamp', 'asc')->limit(1)->get();
        } else {
            $results = DB::table('gw_users')->where('company_id', $company_id)->orderBy('creation_timestamp', 'asc')->limit(1)->get();
        }

        DB::setFetchMode(PDO::FETCH_CLASS);

        $result = null;
        if (count($results) > 0) {
            $result = $results[0];
        }

        return $result;
    }

    public static function sendSns($userObject, $action) {
        $messageData = array();

        $messageData['data'] = array("id" => $userObject->user_id,
            "companyId" => $userObject->company_id,
            "sfContactId" => $userObject->sf_contact_id,
            "prospectId" => $userObject->gw_prospect_id,
            "firstname" => $userObject->first_name,
            "lastname" => $userObject->last_name,
            "status" => $userObject->status,
            "sfContactStatus" => $userObject->sf_contact_status,
            "email" => $userObject->email,
            "phoneCountryCode" => $userObject->phone_country_code,
            "phoneNumber" => $userObject->phone_number,
            "phoneExtension" => $userObject->phone_extension
        );

        if (!empty($userObject->primary_user)) {
            $messageData['data']['primaryUser'] = (bool) $userObject->primary_user;
        } else {
            $messageData['data']['primaryUser'] = true;
        }

        if (!empty($userObject->is_activated)) {
            $messageData['data']['isActivated'] = (bool) $userObject->is_activated;
        } else {
            $messageData['data']['isActivated'] = false;
        }

        if (!empty($userObject->invitation_sent)) {
            $messageData['data']['invitationSent'] = strtotime($userObject->invitation_sent);
        } else {
            $messageData['data']['invitationSent'] = NULL;
        }

        if (!empty($userObject->invitation_expired)) {
            $messageData['data']['invitationExpired'] = strtotime($userObject->invitation_expired);
        } else {
            $messageData['data']['invitationExpired'] = NULL;
        }

        if (!empty($userObject->account_activated)) {
            $messageData['data']['accountActivated'] = strtotime($userObject->account_activated);
        } else {
            $messageData['data']['accountActivated'] = NULL;
        }

        $messageData['JsonVersion'] = '1.0.0';
        $messageData['messageTime'] = \gmdate('c');

        $messageJson = json_encode($messageData);

        $lastModified = strtotime($userObject->modified_timestamp);
        $properties = AwsTools::getMessageProperties('User', $userObject->user_id, $userObject->email, $action, $lastModified);

        $snsClient = AwsTools::getSnsClient(getenv('SNS_REGION'));
        $queueOrTopicName = getenv("USERS_SNS_ARN");

        AwsTools::sendMessage($snsClient, $queueOrTopicName, $userObject->user_id, $messageJson, null, $properties);
    }

    public static function sendInvite($userDetails, $expiryDate, $isActivate) {
        $hostName = getenv("APP_URL");

        $sentTime = time();

        $activation_id = \uniqid("", true);
        $keyParams = "userId=" . $userDetails['user_id'] . "&expiryDate=" . $expiryDate . "&activationId=" . $activation_id;
        if ($isActivate) {
            $activationUrl = $hostName . "/activate/" . base64_encode($keyParams);
        } else {
            $activationUrl = $hostName . "/resetpassword/" . base64_encode($keyParams);
        }

        self::updateSentDetails($userDetails['user_id'], $isActivate, $sentTime, $expiryDate, $activation_id);
        self::sendMail($userDetails['email'], $isActivate, array('activationUrl' => $activationUrl, 'name' => $userDetails['first_name']));
    }

    private static function updateSentDetails($userId, $isActivate, $sentTime, $expiryTime, $activation_id) {
        $updateInfo = array(
            'invitation_sent' => date("Y-m-d H:i:s", $sentTime),
            'invitation_expired' => date("Y-m-d H:i:s", $expiryTime),
            'modified_timestamp' => date("Y-m-d H:i:s", $sentTime)
        );

        if ($isActivate) {
            $updateInfo['status'] = 'awaiting activation';
            $updateInfo['activation_id'] = $activation_id;
        } else {
            $updateInfo['status'] = 'awaiting pwd reset';
            $updateInfo['forgot_pwd_request_id'] = $activation_id;
        }

        DB::table('gw_users')->where('user_id', $userId)->update($updateInfo);
    }

    private static function sendMail($toAddress, $isActivate, $params) {
        if ($isActivate) {
            $template = "activation_email";
            $subject = "Your portal account at Gateway Supply Chain";
        } else {
            $template = "resetpwd_email";
            $subject = "Gateway Supply Chain Account: Reset Password";
        }

        $from = getenv("MAIL_FROM_ADDRESS");
        $emalNotifier = new EmailNotifier($subject, $template, $from, $toAddress);
        $emalNotifier->send($params);
    }

    public static function createUser(array $inputData, array $userInfo) {
        Utils::verifyAndAdd($inputData, $userInfo, 'first_name', 'first_name');
        Utils::verifyAndAdd($inputData, $userInfo, 'last_name', 'last_name');
        Utils::verifyAndAdd($inputData, $userInfo, 'email', 'email');
        Utils::verifyAndAdd($inputData, $userInfo, 'country_code', 'phone_country_code');
        Utils::verifyAndAdd($inputData, $userInfo, 'phone_number', 'phone_number');
        Utils::verifyAndAdd($inputData, $userInfo, 'extension', 'phone_extension');

        Utils::verifyAndAdd($inputData, $userInfo, 'company_id', 'company_id');
        if (Utils::validateNumberField($inputData, 'primary_user')) {
            $userInfo['primary_user'] = $inputData['primary_user'];
        }

        Utils::verifyAndAdd($inputData, $userInfo, 'status', 'status');

        if (!array_key_exists('status', $userInfo)) {
            $userInfo['status'] = 'pending';
        } else if (strcmp($userInfo['status'], 'activated') == 0) {
            $userInfo['account_activated'] = DB::raw('coalesce( account_activated , \'' . date("Y-m-d H:i:s", time()) . '\')');
            $userInfo['is_activated'] = true;
        }

        Utils::verifyAndAdd($inputData, $userInfo, 'company_status', 'company_status');
        Utils::verifyAndAdd($inputData, $userInfo, 'sf_account_id', 'sf_account_id');
        Utils::verifyAndAdd($inputData, $userInfo, 'sf_contact_id', 'sf_contact_id');
        Utils::verifyAndAdd($inputData, $userInfo, 'sf_contact_status', 'sf_contact_status');
        if (Utils::validateNumberField($inputData, 'gw_prospect_id')) {
            $userInfo['gw_prospect_id'] = $inputData['gw_prospect_id'];
        }

        $userInfo['creation_timestamp'] = date("Y-m-d H:i:s", time());
        $userInfo['modified_timestamp'] = date("Y-m-d H:i:s", time());

        return DB::table('gw_users')->insertGetId($userInfo);
    }

    public static function updateUser($auth_user_id, array $inputData, array $userInfo, $isAdmin, $idField) {
        $keyId = $inputData[$idField];

        Utils::verifyAndAdd($inputData, $userInfo, 'first_name', 'first_name');
        Utils::verifyAndAdd($inputData, $userInfo, 'last_name', 'last_name');
        Utils::verifyAndAdd($inputData, $userInfo, 'country_code', 'phone_country_code');
        Utils::verifyAndAdd($inputData, $userInfo, 'phone_number', 'phone_number');
        Utils::verifyAndAdd($inputData, $userInfo, 'extension', 'phone_extension');

        if ($isAdmin) {
            Utils::verifyAndAdd($inputData, $userInfo, 'email', 'email');
            Utils::verifyAndAdd($inputData, $userInfo, 'company_id', 'company_id');
            if (Utils::validateNumberField($inputData, 'primary_user')) {
                $userInfo['primary_user'] = $inputData['primary_user'];
            }

            if (Utils::validateStringField($inputData, 'status') && strcmp($inputData['status'], 'activated') == 0) {
                $userInfo['account_activated'] = DB::raw('coalesce( account_activated , \'' . date("Y-m-d H:i:s", time()) . '\')');
                $userInfo['is_activated'] = true;
            } else if (Utils::validateStringField($inputData, 'status') && strcmp($inputData['status'], 'inactive') == 0) {
                DB::table('gw_users')->where($idField, $keyId)->update(array('previous_status' => DB::raw('status')));
            }

            if (!Utils::validateStringField($inputData, 'status')) {
                DB::table('gw_users')->where($idField, $keyId)->where('status', 'inactive')->update(array('status' => DB::raw('previous_status')));
                DB::table('gw_users')->where($idField, $keyId)->where('status', 'draft')->update(array('status' => "pending"));
            } else {
                Utils::verifyAndAdd($inputData, $userInfo, 'status', 'status');
            }

            Utils::verifyAndAdd($inputData, $userInfo, 'company_status', 'company_status');
            Utils::verifyAndAdd($inputData, $userInfo, 'sf_account_id', 'sf_account_id');
            Utils::verifyAndAdd($inputData, $userInfo, 'sf_contact_id', 'sf_contact_id');
            Utils::verifyAndAdd($inputData, $userInfo, 'sf_contact_status', 'sf_contact_status');
            if (Utils::validateNumberField($inputData, 'gw_prospect_id')) {
                $userInfo['gw_prospect_id'] = $inputData['gw_prospect_id'];
            }
        }

        $userInfo['modified_timestamp'] = date("Y-m-d H:i:s", time());

        if (Utils::validateNumberField($inputData, 'sf_modified_timestamp')) {
            $sf_last_modified_date = $inputData['sf_modified_timestamp'];
            $userInfo['sf_modified_timestamp'] = $sf_last_modified_date;
            DB::table('gw_users')->where($idField, $keyId)->where('sf_modified_timestamp', '<', $sf_last_modified_date)->update($userInfo);
        } else {

            DB::table('gw_users')->where($idField, $keyId)->update($userInfo);
        }

        if (empty($auth_user_id)) {
            if (array_key_exists('email', $userInfo)) {
                $auth_user_id = self::getAuthUserIdByEmail($userInfo['email']);
            }
        }

        $updateAuthUserData = array();
        if ($isAdmin) {
            $updateAuthUserData = array("app_metadata" => array(
                    "gw_user_id" => $keyId,
                    "roles" => array("gw_user", "gw_admin"),
                    "name" => Utils::validateStringField($userInfo, 'first_name') ? $userInfo['first_name'] : $userInfo['last_name'],
                    "gw_company_id" => $userInfo['company_id']));
        } else {
            $updateAuthUserData = array("app_metadata" => array(
                    "name" => Utils::validateStringField($userInfo, 'first_name') ? $userInfo['first_name'] : $userInfo['last_name'])
            );
        }

        self::updateAuthUser($auth_user_id, $updateAuthUserData);
    }

    public static function inactivateUsers($idField, $keyId) {
        DB::table('gw_users')->where($idField, $keyId)->update(array('is_activated' => false, 'company_status' => 'inactive'));
    }

    public static function activateUsers($idField, $keyId) {
        DB::table('gw_users')->where($idField, $keyId)->update(array('company_status' => 'approved'));
        DB::table('gw_users')->where(array($idField => $keyId, 'status' => 'activated'))->update(array('is_activated' => true));
    }

    public static function createAuthUser($userObject, $email, $password) {
        $updateParams = null;
        if (Utils::validateBooleanField(getenv("ENABLE_AZURE_DEVELOPER_USER")) && empty($userObject->api_key)) {
            $developerUserDetails = array('firstName' => $userObject->first_name, 'lastName' => $userObject->last_name, 'email' => $email);
            $updateParams = AzureService::createDeveloperUserAndGetApiKey($developerUserDetails);
        }

        self::updateUserStatus($userObject->user_id, 'activated', $updateParams);

        $adminAccessToken = self::getAdminAccessToken();
        $authUserResponse = self::createAuth0User($adminAccessToken, $userObject, $email, $password);

        if (!array_key_exists('user_id', $authUserResponse) || empty($authUserResponse['user_id'])) {
            Log::error("User creation failed: " . json_encode($authUserResponse, true));
            throw new \Exception(trans('messages.account_create_failed'));
        }
    }

    public static function updateAuthUser($auth_user_id, $updateUserData) {
        if (Utils::validateStringField($auth_user_id)) {
            $adminAccessToken = self::getAdminAccessToken();

            $userData = new Management($adminAccessToken['access_token'], getenv("OAUTH_BASE_DOMAIN"), Utils::getGuzzleOptions());

            $userData->users->update($auth_user_id, $updateUserData);
        }
    }

    public static function activateAuthUser($userObject, $auth_user_id, $status = 'activated') {
        if (Utils::validateStringField($auth_user_id)) {
            $updateParams = null;
            if (Utils::validateBooleanField(getenv("ENABLE_AZURE_DEVELOPER_USER")) && empty($userObject->api_key)) {
                $developerUserDetails = array('firstName' => $userObject->first_name, 'lastName' => $userObject->last_name, 'email' => $userObject->email);

                $updateParams = AzureService::createDeveloperUserAndGetApiKey($developerUserDetails);
            }

            self::updateUserStatus($userObject->user_id, $status, $updateParams);

            $updateAuthUserData = array("app_metadata" => array(
                    "gw_user_id" => $userObject->user_id,
                    "roles" => array("gw_user", "gw_admin"),
                    "name" => Utils::validateStringField($userObject->first_name) ? $userObject->first_name : $userObject->last_name,
                    "gw_company_id" => $userObject->company_id));

            self::updateAuthUser($auth_user_id, $updateAuthUserData);
        }
    }

    public static function updateUserStatus($user_id, $status = 'activated', $params = null) {
        $userInfo = array();

        $userInfo['status'] = $status;
        if (strcmp($status, 'activated') == 0) {
            $userInfo['account_activated'] = DB::raw('coalesce( account_activated , \'' . date("Y-m-d H:i:s", time()) . '\')');
            $userInfo['is_activated'] = true;
        }
        $userInfo['modified_timestamp'] = date("Y-m-d H:i:s", time());

        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $userInfo[$key] = $value;
            }
        }

        DB::table('gw_users')->where('user_id', $user_id)->update($userInfo);
    }

    public static function resetPassword($userObject, $user_id, $new_password) {
        self::changePassword($user_id, $new_password);

        $userInfo = array();

        $userInfo['status'] = 'activated';
        if (empty($userObject->account_activated)) {
            $userInfo['account_activated'] = date("Y-m-d H:i:s", time());
        }
        $userInfo['modified_timestamp'] = date("Y-m-d H:i:s", time());

        DB::table('gw_users')->where('user_id', $userObject->user_id)->update($userInfo);
    }

    public static function changePassword($user_id, $new_password) {
        $adminAccessToken = self::getAdminAccessToken();
        self::changeAuthPassword($adminAccessToken, $user_id, $new_password);
    }

    private static function getAuth0DbConnnectionName() {
        $auth0_db_connection_name = getenv("AUTH0_GATEWAY_CONNECTION_NAME");
        if (empty($auth0_db_connection_name)) {
            $auth0_db_connection_name = "Username-Password-Authentication";
        }

        return $auth0_db_connection_name;
    }

    private static function changeAuthPassword($adminAccessToken, $user_id, $new_password) {
        $auth0_db_connection_name = self::getAuth0DbConnnectionName();

        $updateUserData = array(
            "password" => $new_password,
            "connection" => $auth0_db_connection_name);
        $userData = new Management($adminAccessToken['access_token'], getenv("OAUTH_BASE_DOMAIN"), Utils::getGuzzleOptions());

        $userData->users->update($user_id, $updateUserData);
    }

    private static function getAdminAccessToken() {
        $client_id = getenv("OAUTH_ADMIN_CLIENT_ID");
        $client_secret = getenv("OAUTH_ADMIN_CLIENT_SECRET");

        $api = new Authentication(config("laravel-auth0.domain"), $client_id, $client_secret, Utils::getGuzzleOptions());

        $token = $api->oauth_token($client_id, $client_secret, "client_credentials", null, getenv("OAUTH_ADMIN_AUDIENCE"));

        return $token;
    }

    private static function createAuth0User($userAccessToken, $userObject, $email, $password) {
        $auth0_db_connection_name = self::getAuth0DbConnnectionName();

        $createUserData = array(
            "connection" => $auth0_db_connection_name,
            "email" => $email,
            "password" => $password,
            "email_verified" => true,
            "verify_email" => false,
            "app_metadata" => array(
                "gw_user_id" => $userObject->user_id,
                "roles" => array("gw_user", "gw_admin"),
                "name" => Utils::validateStringField($userObject->first_name) ? $userObject->first_name : $userObject->last_name,
                "gw_company_id" => $userObject->company_id)
        );

        $userData = new Management($userAccessToken['access_token'], getenv("OAUTH_BASE_DOMAIN"), Utils::getGuzzleOptions());

        return $userData->users->create($createUserData);
    }

    public static function authenticateWithRO($current_password, $email) {
        $client_id = getenv("OAUTH_BASE_CLIENT_ID");
        $client_secret = getenv("OAUTH_BASE_CLIENT_SECRET");
        $auth0_db_connection_name = self::getAuth0DbConnnectionName();

        $api = new Authentication(config("laravel-auth0.domain"), $client_id, $client_secret, Utils::getGuzzleOptions());

        $authData = $api->authorize_with_ro($email, $current_password, 'openid', $auth0_db_connection_name);

        if ($authData && isset($authData['id_token']) && !empty($authData['id_token'])) {
            return $authData['id_token'];
        }

        return NULL;
    }

    public static function updateCompanyIdBySfAccountId($sfAccountId, $company_id) {
        DB::table('gw_users')
                ->where('sf_account_id', $sfAccountId)
                ->whereRaw('( company_id is NULL or company_id = 0 )')
                ->update(array('company_id' => $company_id));
    }

    public static function getAuthUserIdByEmail($email) {
        $adminAccessToken = self::getAdminAccessToken();

        return self::getAuthU0serIdByEmail($adminAccessToken, $email);
    }

    private static function getAuthU0serIdByEmail($adminAccessToken, $email) {
        $userData = new Management($adminAccessToken['access_token'], getenv("OAUTH_BASE_DOMAIN"), Utils::getGuzzleOptions());

        $results = $userData->users->search(array("field" => "user_id", "q" => "email.raw:" . $email));

        $auth_user_id = NULL;
        if (count($results) > 0) {
            foreach ($results as $result) {
                if (array_key_exists("identities", $result)) {
                    $identities = $result["identities"];
                    if (count($identities) > 0) {
                        foreach ($identities as $identity) {
                            if (array_key_exists("connection", $identity)) {
                                if (strcmp($identity["connection"], getenv("AUTH0_GATEWAY_CONNECTION_NAME")) == 0) {
                                    $auth_user_id = $result['user_id'];
                                    break 2;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $auth_user_id;
    }

    public static function getUserFullnameById($user_id, $userMap) {
        $fullName = NULL;
        if (is_null($userMap)) {
            $userDetails = UserService::getUserAsArrayById($user_id);

            $fullName = $userDetails['first_name'];
            if (!empty($fullName) && !empty($userDetails['last_name'])) {
                $fullName .= ', ' . $userDetails['last_name'];
            }
            if (empty($fullName) && !empty($userDetails['last_name'])) {
                $fullName .= $userDetails['last_name'];
            }
        } else {
            if (empty($userMap) || !array_key_exists($user_id, $userMap)) {
                $userDetails = UserService::getUserAsArrayById($user_id);

                $fullName = $userDetails['first_name'];
                if (!empty($fullName) && !empty($userDetails['last_name'])) {
                    $fullName .= ', ' . $userDetails['last_name'];
                }
                if (empty($fullName) && !empty($userDetails['last_name'])) {
                    $fullName .= $userDetails['last_name'];
                }
                $userMap['user_id'] = $fullName;
            } else {
                $fullName = $userMap['user_id'];
            }
            $userMap['user_id'] = $fullName;
        }

        return $fullName;
    }

    public static function regenerateApiKey($userId) {
        $userObject = self::getUserById($userId);
        $apiKey = AzureService::regenerateApiKey($userObject->az_subscription_id);
        DB::table('gw_users')->where('user_id', $userId)->update(array("api_key" => $apiKey));

        return $apiKey;
    }

}
