<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Services\CompanyService;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use \App\Services\Utils;
use \Illuminate\Support\Facades\URL;

class UserController extends Controller {

    /**
     * Gateway Admin endpoint to create/update user
     *
     * @param Request $request
     * @return type
     */
    public function upsert(Request $request) {
        $input_data = $request->json()->all();

        if (isset($input_data)) {
            $rules = array(
                'last_name' => 'required',
                'email' => 'required'
            );

            $validator = Validator::make($input_data, $rules);

            if ($validator->fails()) {
                $failureMsg = trans('messages.mandatory_head');
                $temp = $validator->errors()->all();
                $errorMessage = $failureMsg . implode(", ", $temp);

                return $this->logErrorAndSendResponse($errorMessage);
            } else {
                $client = $request->header('client');
                $input_data['client'] = $client;

                return $this->upsertUser($input_data, true);
            }
        } else {
            return $this->logErrorAndSendResponse(trans('messages.payload_missing'));
        }
    }

    /**
     * Gateway User endpoint to update his profile
     *
     * @param Request $request
     * @return type
     */
    public function update(Request $request) {
        $input_data = $request->json()->all();

        if (isset($input_data)) {
            $rules = array(
                'last_name' => 'required',
                'email' => 'required'
            );

            $validator = Validator::make($input_data, $rules);

            if ($validator->fails()) {
                $failureMsg = trans('messages.mandatory_head');
                $temp = $validator->errors()->all();
                $errorMessage = $failureMsg . implode(", ", $temp);

                return $this->logErrorAndSendResponse($errorMessage);
            } else {
                $input_data['user_id'] = $_REQUEST['AUTHUSERDATA']['gw_user_id'];
                $input_data['company_id'] = $_REQUEST['AUTHUSERDATA']['gw_company_id'];

                return $this->upsertUser($input_data, false);
            }
        } else {
            return $this->logErrorAndSendResponse(trans('messages.payload_missing'));
        }
    }

    /**
     * Gateway User endpoint to his profile info
     *
     * @param Request $request
     * @return type
     */
    public function index(Request $request) {
        $user_id = $_REQUEST['AUTHUSERDATA']['gw_user_id'];

        try {
            $result = UserService::getUserAsArrayById($user_id);
            if (!empty($result)) {
                $user_data = array();

                $user_data['first_name'] = $result['first_name'];
                $user_data['last_name'] = $result['last_name'];
                $user_data['email'] = $result['email'];
                $user_data['country_code'] = $result['phone_country_code'];
                $user_data['phone_number'] = $result['phone_number'];
                $user_data['extension'] = $result['phone_extension'];
                $user_data['status'] = $result['status'];
                $user_data['primary_user'] = $result['primary_user'];
                $user_data['is_activated'] = $result['is_activated'];
                $user_data['account_activated'] = $result['account_activated'];

                $company = CompanyService::getCompanyById($result['company_id']);
                $company_type = $company->company_type;
                if (!empty($company_type) && $company_type !== "") {
                    $user_data['apikey'] = $result['api_key'];
                }

                return $this->sendSuccessResponse($user_data);
            } else {
                return $this->logErrorAndSendResponse(trans('messages.user_requested_not_found'));
            }
        } catch (Exception $exception) {
            return $this->logExceptionAndSendResponse($exception, trans('messages.user_request_failed'));
        }
    }

    /**
     * Gateway User endpoint to get his auth token info
     *
     * @return type
     */
    public function getTokenInfo() {
        try {
            $user_id = $_REQUEST['AUTHUSERDATA']['gw_user_id'];

            $result = UserService::getUserAsArrayWithCompanyInfo($user_id);
            if (!empty($result)) {
                $user_data = array();

                $user_data['first_name'] = $result['first_name'];
                $user_data['last_name'] = $result['last_name'];
                $user_data['email'] = $result['email'];
                $user_data['status'] = $result['status'];
                $user_data['is_activated'] = $result['is_activated'];
                $user_data['primary_user'] = $result['primary_user'];
                $user_data['contractSigned'] = $result['signed_agreement'];
                $user_data['roles'] = $_REQUEST['AUTHUSERDATA']['roles'];

                return $this->sendSuccessResponse($user_data);
            } else {
                return $this->logErrorAndSendResponse(trans('messages.user_requested_not_found'));
            }
        } catch (\Exception $exception) {
            return $this->logExceptionAndSendResponse($exception, trans('messages.error_on_auth_token_info'));
        }
    }

    /**
     * Update or insert user
     *
     * @param type $userData
     * @param type $isAdmin
     * @return type
     */
    private function upsertUser($userData, $isAdmin) {
        // Process the user data
        try {
            $auth_user_id = NULL;
            if (!$isAdmin) {
                $auth_user_id = $_REQUEST['AUTHUSERDATA']['user_id'];
            }

            $company_id = NULL;
            $company_status = NULL;
            $companyFound = false;
            if (Utils::validateNumberField($userData, 'company_id')) {
                $company_id = $userData['company_id'];

                $result = CompanyService::getCompanyAsArrayById($company_id, ['status']);
                if (!empty($result)) {
                    $companyFound = true;
                    $company_status = $result['status'];
                }
            } else if (Utils::validateStringField($userData, 'sf_account_id')) {
                $result = CompanyService::getCompanyAsArrayBySfAccountId($userData['sf_account_id'], ['company_id', 'status']);
                if (!empty($result)) {
                    $companyFound = true;
                    $company_id = $result['company_id'];
                    $company_status = $result['status'];

                    $userData['company_id'] = $company_id;
                }
            }

            if (!$companyFound) {
                return $this->logErrorAndSendResponse(trans('messages.company_id_incorrect'));
            }

            $userData['company_status'] = $company_status;

            DB::beginTransaction();

            $userInfo = array();
            $ops_status = '';
            $user_id = NULL;
            $action = NULL;
            if (Utils::validateNumberField($userData, 'user_id')) {
                $user_id = $userData['user_id'];

                UserService::updateUser($auth_user_id, $userData, $userInfo, $isAdmin, 'user_id');

                $action = 'Update';
                $ops_status = 'updated';
            } else if (Utils::validateStringField($userData, 'sf_contact_id')) {
                $user_id = UserService::getUserIdBySfContactId($userData['sf_contact_id']);

                if (Utils::validateNumberField($user_id)) {
                    $userData['user_id'] = $user_id;
                    UserService::updateUser($auth_user_id, $userData, $userInfo, $isAdmin, 'user_id');

                    $action = 'Update';
                    $ops_status = 'updated';
                } else {
                    $user_id = UserService::createUser($userData, $userInfo);

                    $action = 'Create';
                    $ops_status = 'added';
                }
            } else {
                $user_id = UserService::createUser($userData, $userInfo);

                $action = 'Create';
                $ops_status = 'added';
            }

            $userDetails = array();
            $userDetails['user_id'] = $user_id;
            if (Utils::check_keys_in_array($userInfo, ['email', 'first_name', 'status'])) {
                $userDetails['first_name'] = $userInfo['first_name'];
                $userDetails['email'] = $userInfo['email'];
                $userDetails['status'] = $userInfo['status'];
            } else {
                $result = UserService::getUserAsArrayById($user_id);

                $userDetails['first_name'] = $result['first_name'];
                $userDetails['email'] = $result['email'];
                $userDetails['status'] = $result['status'];
            }

            $is_activated = false;
            if ($isAdmin && Utils::validateNumberField($company_id)) {
                $is_activated = UserService::sendActivationMail($company_status, $userDetails);
            }

            //If is from SF, don't publish the events back
            if ($is_activated || !array_key_exists("client", $userData) || strcasecmp($userData["client"], "salesforce") != 0) {
                // Get the updated user data and publish to SNS
                $userObject = UserService::getUserById($user_id);
                UserService::sendSns($userObject, $action);
            }

            DB::commit();

            return $this->sendSuccessResponse(NULL, 'User Information is ' . $ops_status);
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->logExceptionAndSendResponse($exception, trans('messages.user_processing_failed'));
        }
    }

    /**
     * Gateway User endpoint to activate his account
     *
     * @param Request $request
     * @param type $token
     * @return type
     */
    public function activateUser(Request $request, $token) {
        try {
            if (empty($token)) {
                $errorMessage = trans('messages.activation_token_missing');
                Log::error($errorMessage);

                return view('activateuser', ['hasError' => true, 'errorMessage' => $errorMessage]);
            }

            $validationResult = $this->validateUserToken($token, 'activation_id');

            if ($validationResult['hasError']) {
                $errorMessage = $validationResult['errorMessage'];
                Log::error($errorMessage);

                return view('activateuser', ['hasError' => true, 'errorMessage' => $errorMessage]);
            }

            $user = $validationResult['user'];

            $email = $user->email;
            $password = $request->input("password");
            if (empty($email) || empty($password)) {
                $errorMessage = trans('messages.user_data_missing');
                Log::error($errorMessage);

                return view('activateuser', ['hasError' => true, 'errorMessage' => $errorMessage]);
            }

            DB::beginTransaction();

            UserService::createAuthUser($user, $email, $password);

            DB::commit();

            try {
                $access_token = UserService::authenticateWithRO($password, $email);
                if (empty($access_token)) {
                    $errorMessage = trans('messages.account_create_success');
                    return view('activateuser', ['hasError' => true, 'errorMessage' => $errorMessage]);
                } else {
                    return view('activateuser', ['authToken' => $access_token, 'hasError' => false]);
                }
            } catch (\Exception $ex) {
                $errorMessage = trans('messages.account_create_success');
                Log::error($errorMessage . ":" . $ex->getMessage() . ":" . $ex->getTraceAsString());

                app('sentry')->captureException($ex);

                return view('activateuser', ['hasError' => false, 'errorMessage' => $errorMessage]);
            }
        } catch (RequestException $requestException) {
            DB::rollBack();

            $errorMessage = trans('messages.account_create_failed');
            $responseSummary = $requestException->getResponseBodySummary($requestException->getResponse());
            if (!empty($responseSummary)) {
                $authResponse = json_decode($responseSummary, true);
                $errorMessage .= $authResponse['message'];
            }

            $this->logErrorAndSendToSentry($requestException, $errorMessage);

            return view('activateuser', ['hasError' => true, 'errorMessage' => $errorMessage]);
        } catch (\Exception $exception) {
            DB::rollBack();

            $errorMessage = trans('messages.account_create_failed');
            $this->logErrorAndSendToSentry($exception, $errorMessage);

            return view('activateuser', ['hasError' => true, 'errorMessage' => $errorMessage]);
        }
    }

    /**
     * Gateway User endpoint to change password
     *
     * @param Request $request
     * @return type
     */
    public function changepassword(Request $request) {
        try {
            $email = $_REQUEST['AUTHUSERDATA']['email'];

            $input_data = $request->json()->all();

            if (!isset($input_data)) {
                $errorMessage = trans('messages.password_data_missing');
                return $this->logErrorAndSendResponse($errorMessage);
            } else {
                $rules = array(
                    'current_password' => 'required',
                    'new_password' => 'required'
                );

                $validator = Validator::make($input_data, $rules);

                if ($validator->fails()) {
                    $failureMsg = trans('messages.mandatory_head');

                    $temp = $validator->errors()->all();
                    $errorMessage = $failureMsg . implode(", ", $temp);

                    return $this->logErrorAndSendResponse($errorMessage);
                }
            }

            $accessToken = UserService::authenticateWithRO($input_data['current_password'], $email);

            if (!empty($accessToken)) {
                UserService::changePassword($_REQUEST['AUTHUSERDATA']['user_id'], $input_data['new_password']);
                return $this->sendSuccessResponse(NULL, trans('messages.password_changed'));
            } else {
                $errorMessage = trans('messages.current_password_incorrect');
                return $this->logErrorAndSendResponse($errorMessage);
            }
        } catch (RequestException $requestException) {
            $errorMessage = trans('messages.current_password_incorrect');
            return $this->logExceptionAndSendResponse($requestException, $errorMessage);
        } catch (\Exception $exception) {
            $errorMessage = trans('messages.change_password_fail');
            return $this->logExceptionAndSendResponse($exception, $errorMessage);
        }
    }

    /**
     * Gateway User endpoint to forgot password
     *
     * @param Request $request
     * @return type
     */
    public function forgotpassword(Request $request) {
        try {
            $email = $request->header("email");
            $input_data = array('email' => $email);

            if (empty($email)) {
                $errorMessage = trans('messages.email_data_missing');
                return $this->logErrorAndSendResponse($errorMessage);
            } else {
                $rules = array(
                    'email' => 'required|email',
                );

                $validator = Validator::make($input_data, $rules);

                if ($validator->fails()) {
                    $failureMsg = trans('messages.mandatory_head');

                    $temp = $validator->errors()->all();
                    $errorMessage = $failureMsg . implode(", ", $temp);

                    return $this->logErrorAndSendResponse($errorMessage);
                }
            }

            $hasError = false;
            // verify the user id by email found or not
            $userDetails = UserService::getUserByEmail($email, ['awaiting pwd reset', 'activated', 'awaiting activation', 'inactive']);

            if (!empty($userDetails)) {
                $expiryDate = strtotime(UserService::getExpirationInDays());

                DB::beginTransaction();

                if (strcmp($userDetails['status'], 'inactive') == 0 ||
                        strcmp($userDetails['company_status'], 'inactive') == 0) {
                    $hasError = true;
                    $errorMessage = trans('messages.account_inactive');
                } else if (Utils::validateBooleanField($userDetails['is_activated'])) {
                    UserService::sendInvite($userDetails, $expiryDate, false);
                } else { // User not activated yet
                    UserService::sendInvite($userDetails, $expiryDate, true);
                }
            } else {
                $hasError = true;
                $errorMessage = trans('messages.account_no_exists');
            }

            if ($hasError) {
                return $this->logErrorAndSendResponse($errorMessage);
            } else {
                DB::commit();

                return $this->sendSuccessResponse(NULL, trans('messages.forgot_pwd_mail_sent'));
            }
        } catch (\Exception $exception) {
            DB::rollback();

            $errorMessage = trans('messages.forgot_pwd_failed');
            return $this->logExceptionAndSendResponse($exception, $errorMessage);
        }
    }

    /**
     * Gateway User endpoint to reset password
     *
     * @param Request $request
     * @param type $token
     * @return type
     */
    public function resetPassword(Request $request) {
        $hasError = true;
        $errorMessage = NULL;

        try {
            $token = $request->input('activationId');

            if (empty($token)) {
                $errorMessage = trans('messages.reset_pwd_token_missing');
            } else {
                $validationResult = $this->validateUserToken($token, 'forgot_pwd_request_id');

                if ($validationResult['hasError']) {
                    $errorMessage = $validationResult['errorMessage'];
                } else {
                    $userObject = $validationResult['user'];
                    $new_password = $request->input("password");

                    if (empty($new_password)) {
                        $errorMessage = trans('messages.user_data_missing');
                    } else {
                        $auth0_user_id = UserService::getAuthUserIdByEmail($userObject->email);

                        if (empty($auth0_user_id)) {
                            $errorMessage = trans('messages.account_no_exists');
                        } else {
                            DB::beginTransaction();

                            UserService::resetPassword($userObject, $auth0_user_id, $new_password);

                            DB::commit();

                            $hasError = false;
                        }
                    }
                }
            }
        } catch (\Exception $exception) {
            DB::rollBack();

            $errorMessage = trans('messages.reset_pwd_failed');
            $this->logErrorAndSendToSentry($exception, $errorMessage);
        }

        return view('resetconfirm', [
            'authDomain' => getenv('OAUTH_BASE_DOMAIN'),
            'cdnlocation' => getenv('CDN_LOCATION'),
            'authId' => getenv('OAUTH_BASE_CLIENT_ID'),
            'hasError' => $hasError,
            'errorMessage' => $errorMessage]);
    }

    /**
     * Gateway User endpoint to request for activation email
     *
     * @param Request $request
     * @return type
     */
    public function resendActivationEmail(Request $request) {
        $token = $request->header('activationtoken');

        try {
            if (empty($token)) {
                $errorMessage = trans('messages.expired_token_missing');
                return $this->logErrorAndSendResponse($errorMessage);
            }

            $params = base64_decode($token);
            $params = explode('&', $params);

            $userJson = NULL;
            $hasError = true;
            $errorMessage = trans('messages.user_info_not_avail');
            if (count($params) == 3) {
                $userId = str_replace("userId=", "", $params[0]);
                $expiryDate = str_replace("expiryDate=", "", $params[1]);
                $activation_id = str_replace("activationId=", "", $params[2]);
            } else {
                $errorMessage = trans('messages.expired_token_mismatch');
                return $this->logErrorAndSendResponse($errorMessage);
            }

            $user = NULL;
            if (!is_null($userId)) {
                $user = UserService::getUserById($userId);

                if (!is_null($user)) {
                    if (strcmp($user->status, 'inactive') == 0 ||
                            strcmp($user->company_status, 'inactive') == 0) {
                        $errorMessage = trans('messages.account_inactive');
                    } else if (Utils::validateBooleanField($user->is_activated)) {
                        $url = URL::to('/');
                        $errorMessage = trans('messages.already_activated_msg', ['name' => $url]);
                    } else if (strcmp($user->activation_id, $activation_id) == 0) {
                        $currTime = time();
                        if ($currTime >= strtotime($user->invitation_expired)) {
                            $userDetails['first_name'] = $user->first_name;
                            $userDetails['user_id'] = $user->user_id;
                            $userDetails['email'] = $user->email;

                            DB::beginTransaction();

                            $expiryDate = strtotime(UserService::getExpirationInDays());
                            UserService::sendInvite($userDetails, $expiryDate, true);

                            DB::commit();

                            $hasError = false;
                            $errorMessage = '';
                            $userJson = array('email' => $user->email);
                        }
                    } else {
                        $errorMessage = trans('messages.activation_link_wrong');
                    }
                }
            }

            if ($hasError) {
                return $this->logErrorAndSendResponse($errorMessage);
            } else {
                return $this->sendSuccessResponse($userJson);
            }
        } catch (Exception $exception) {
            DB::rollBack();

            $errorMessage = trans('messages.resend_activation_fail');
            return $this->logExceptionAndSendResponse($exception, $errorMessage);
        }
    }

    /**
     * Gateway User endpoint to request for activation email
     *
     * @param Request $request
     * @return type
     */
    public function resendPwdEmail(Request $request) {
        $token = $request->header('activationtoken');

        try {
            if (empty($token)) {
                $errorMessage = trans('messages.expired_token_missing');

                return $this->logErrorAndSendResponse($errorMessage);
            }

            $params = base64_decode($token);
            $params = explode('&', $params);

            $userJson = NULL;
            $hasError = true;
            $errorMessage = trans('messages.user_info_not_avail');
            if (count($params) == 3) {
                $userId = str_replace("userId=", "", $params[0]);
                $expiryDate = str_replace("expiryDate=", "", $params[1]);
                $activation_id = str_replace("activationId=", "", $params[2]);
            } else {
                $errorMessage = trans('messages.expired_token_mismatch');
                return $this->logErrorAndSendResponse($errorMessage);
            }

            $user = NULL;
            if (!is_null($userId)) {
                $user = UserService::getUserById($userId);

                if (!is_null($user)) {
                    if (strcmp($user->status, 'inactive') == 0 ||
                            strcmp($user->company_status, 'inactive') == 0) {
                        $errorMessage = trans('messages.account_inactive');
                    } else if (strcmp($user->forgot_pwd_request_id, $activation_id) == 0) {
                        $currTime = time();
                        if ($currTime >= strtotime($user->invitation_expired)) {
                            $userDetails['first_name'] = $user->first_name;
                            $userDetails['user_id'] = $user->user_id;
                            $userDetails['email'] = $user->email;

                            DB::beginTransaction();

                            $expiryDate = strtotime(UserService::getExpirationInDays());
                            UserService::sendInvite($userDetails, $expiryDate, false);

                            DB::commit();

                            $hasError = false;
                            $errorMessage = '';
                            $userJson = array('email' => $user->email);
                        }
                    } else {
                        $errorMessage = trans('messages.forgot_pwd_link_incorrect');
                    }
                }
            }

            if ($hasError) {
                return $this->logErrorAndSendResponse($errorMessage);
            } else {
                return $this->sendSuccessResponse($userJson);
            }
        } catch (Exception $exception) {
            DB::rollBack();

            $errorMessage = trans('messages.resend_forgotpwd_activation_fail');
            return $this->logExceptionAndSendResponse($exception, $errorMessage);
        }
    }

    private function validateUserToken($token, $idField) {
        $params = base64_decode($token);
        $params = explode('&', $params);

        $hasError = true;
        $errorMessage = trans('messages.user_info_not_avail');

        if (count($params) == 3) {
            $userId = str_replace("userId=", "", $params[0]);
            $expiryDate = str_replace("expiryDate=", "", $params[1]);
            $activation_id = str_replace("activationId=", "", $params[2]);
        } else {
            $errorMessage = trans('messages.expired_token_mismatch');
        }

        $user = NULL;
        if (!is_null($userId)) {
            $user = UserService::getUserById($userId);
            if (!is_null($user)) {
                if (strcmp($user->status, 'inactive') == 0) {
                    $errorMessage = trans('messages.account_inactive');
                } else if (strcmp($idField, 'activation_id') == 0 && Utils::validateBooleanField($user->is_activated)) {
                    $url = URL::to('/');
                    $errorMessage = trans('messages.already_activated_msg', ['name' => $url]);
                } else if (strcmp($user->$idField, $activation_id) == 0) {
                    $currTime = time();
                    if (strtotime($user->invitation_expired) >= $currTime) {
                        $hasError = false;
                        $errorMessage = '';
                    } else {
                        $errorMessage = trans('messages.activation_link_wrong');
                    }
                } else {
                    $errorMessage = trans('messages.activation_link_wrong');
                }
            }
        }

        return array('user' => $user, 'hasError' => $hasError, 'errorMessage' => $errorMessage);
    }

    public function validateApiKey(Request $request) {
        $email = $request->header('email');
        $apiKey = $request->header('apiKey');

        try {
            $userDetails = UserService::getUserByApiKey($apiKey);

            if (!empty($userDetails)) {
                if (strcmp($userDetails['email'], $email) != 0) {
                    $errorMessage = trans('messages.api_key_email_not_matching');
                    return $this->logErrorAndSendResponse($errorMessage, NULL, true, 401);
                } else if (strcmp($userDetails['status'], 'inactive') == 0 ||
                        strcmp($userDetails['company_status'], 'inactive') == 0 || !Utils::validateBooleanField($userDetails['is_activated'])) {
                    $errorMessage = trans('messages.api_key_not_active');
                    return $this->logErrorAndSendResponse($errorMessage, NULL, true, 401);
                } else {
                    return $this->sendSuccessResponse(null);
                }
            } else {
                $errorMessage = trans('messages.api_key_not_found');
                return $this->logErrorAndSendResponse($errorMessage, NULL, true, 401);
            }
        } catch (Exception $exception) {
            $errorMessage = trans('messages.validate_api_key_failed');
            return $this->logExceptionAndSendResponse($exception, $errorMessage);
        }
    }

    public function regenerateApiKey(Request $request) {
        $user_id = $_REQUEST['AUTHUSERDATA']['gw_user_id'];

        DB::beginTransaction();
        try {

            $api_key = UserService::regenerateApiKey($user_id);
            DB::commit();

            return $this->sendSuccessResponse(array("apiKey" => $api_key));
        } catch (\Azure\Client\ApiException $apiException) {
            DB::rollBack();
            $apiReponseObject = $apiException->getResponseBody();
            $devMsg = json_encode($apiReponseObject);

            $errorMessage = trans('messages.regenerate_api_key_failed');
            return $this->logExceptionAndSendResponse($apiException, $errorMessage, $devMsg);
        } catch (Exception $exception) {
            DB::rollBack();
            $errorMessage = trans('messages.regenerate_api_key_failed');
            return $this->logExceptionAndSendResponse($exception, $errorMessage);
        }
    }
}
