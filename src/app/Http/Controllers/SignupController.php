<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Exception;
use App\Services\ProspectService;
use \App\Services\UserService;
use \App\Services\Utils;
use \Illuminate\Support\Facades\URL;
use GuzzleHttp\Exception\RequestException;

class SignupController extends Controller {

    public function index(Request $request) {
        $temp = array();

        if (isset($request->firstName) && (!empty($request->firstName))) {
            $firstName = $request->firstName;
        } else {
            $temp[] = 'First name';
        }

        if (isset($request->lastName) && (!empty($request->lastName))) {
            $lastName = $request->lastName;
        } else {
            $temp[] = 'Last name';
        }

        if (!empty($request->email) && isset($request->email)) {
            $email = $request->email;
        } else {
            $temp[] = 'Email';
        }

        if (isset($request->companyName) && (!empty($request->companyName))) {
            $companyName = $request->companyName;
        } else {
            $temp[] = 'Company name';
        }

        if (isset($request->phoneNumber) && (!empty($request->phoneNumber))) {
            $phoneNumber = $request->phoneNumber;
        } else {
            $temp[] = 'Phone number';
        }

        $inputFailure = $temp;
        if (count($inputFailure) > 0) {
            $failureMsg = ttans('messages.signup_missing_head');

            return $this->logErrorAndSendResponse($failureMsg);
        }

        $reqParam = array($request->firstName,
            $request->lastName,
            $request->email,
            $request->companyName,
            $request->phoneNumber,
        );

        Log::info('Processing the company data: ' . json_encode($reqParam));

        $user = DB::table('gw_prospects')->where('email', $email)->where('company_name', $companyName)->first();

        $date = new \DateTime();
        $currentTimestamp = $date->getTimestamp();
        $id = null;
        $action = null;

        if (isset($user)) {
            $id = $user->gw_prospect_id;

            $action = 'Update';
            DB::beginTransaction();

            try {
                DB::table('gw_prospects')
                        ->where('gw_prospect_id', $id)
                        ->update([
                            'first_name' => $firstName,
                            'last_name' => $lastName,
                            'email' => $email,
                            'company_name' => $companyName,
                            'phone_number' => $phoneNumber,
                            'modified_timestamp' => $currentTimestamp,
                ]);

                DB::commit();

                ProspectService::sendSns($id, $firstName, $lastName, $email, $companyName, $phoneNumber, $action, $currentTimestamp);

                Log::info('Inserted the company data and process SNS: ' . json_encode($reqParam));

                return $this->sendSuccessResponse(NULL, trans('messages.company_profile_updated'));
            } catch (Exception $exception) {
                DB::rollBack();
                return $this->logExceptionAndSendResponse($exception, trans('messages.company_updated_failed'));
            }
        } else {
            $action = 'Create';
            DB::beginTransaction();

            try {
                $id = DB::table('gw_prospects')->insertGetId([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'company_name' => $companyName,
                    'phone_number' => $phoneNumber,
                    'creation_timestamp' => $currentTimestamp,
                    'modified_timestamp' => $currentTimestamp,
                        ]
                );

                DB::commit();

                ProspectService::sendSns($id, $firstName, $lastName, $email, $companyName, $phoneNumber, $action, $currentTimestamp);

                Log::info('Created the company data and processed sns: ' . json_encode($reqParam));

                return $this->sendSuccessResponse(NULL, trans('messages.company_profile_added'));
            } catch (\Exception $exception) {
                DB::rollBack();
                return $this->logExceptionAndSendResponse($exception, trans('messages.company_insert_failed'));
            }
        }
    }

    public function activationForm($key) {
        $params = base64_decode($key);
        $params = explode('&', $params);

        $hasError = true;
        $authUserExist = false;

        $errorHeader = trans('messages.activation_form_error_header');
        $errorMessage = trans('messages.activation_form_error_msg');

        if (count($params) == 3) {
            $userId = str_replace("userId=", "", $params[0]);
            $expiryDate = str_replace("expiryDate=", "", $params[1]);
            $activation_id = str_replace("activationId=", "", $params[2]);
        }

        if ($userId && !empty($userId)) {
            $userJson = null;
            $linkExpired = false;
            $userExist = false;
            $email = "";
            $user = UserService::getUserById($userId);

            if (!empty($user)) {
                $email = $user->email;

                if (strcmp($user->status, 'inactive') == 0 ||
                        strcmp($user->company_status, 'inactive') == 0) {
                    $errorMessage = trans('messages.account_inactive');

                    return view('inactiveuser');
                } else if (Utils::validateBooleanField($user->is_activated)) {
                    $userExist = true;
                    $url = URL::to('/');
                    $errorMessage = trans('messages.already_activated_msg', ['name' => $url]);
                } else if (strcmp($user->activation_id, $activation_id) == 0) {
                    $currTime = time();
                    if (strtotime($user->invitation_expired) >= $currTime) {
                        $auth_user_id = UserService::getAuthUserIdByEmail($email);
                        if (!empty($auth_user_id)) {
                            $authUserExist = true;

                            try {
                                DB::beginTransaction();

                                UserService::activateAuthUser($user, $auth_user_id);

                                DB::commit();
                            } catch (RequestException $requestException) {
                                DB::rollBack();

                                $errorMessage = trans('messages.account_activation_failed');
                                $responseSummary = $requestException->getResponseBodySummary($requestException->getResponse());
                                if (!empty($responseSummary)) {
                                    $authResponse = json_decode($responseSummary, true);
                                    $errorMessage .= $authResponse['message'];
                                }

                                $this->logErrorAndSendToSentry($requestException, $errorMessage);
                            } catch (\Exception $exception) {
                                DB::rollBack();

                                $errorMessage = trans('messages.account_activation_failed');

                                $this->logErrorAndSendToSentry($exception, $errorMessage);
                            }
                        } else {
                            $hasError = false;
                        }

                        $userJson = array('email' => $email);
                    } else {
                        $linkExpired = true;
                        $errorMessage = "";
                    }
                }
            }
        }

        return view('activationform', ['userJson' => json_encode($userJson),
            'activationId' => $key,
            'cdnlocation' => getenv('CDN_LOCATION'),
            'authDomain' => getenv('OAUTH_BASE_DOMAIN'),
            'authId' => getenv('OAUTH_BASE_CLIENT_ID'),
            'authUserExist' => $authUserExist,
            'email' => $email,
            'hasError' => $hasError,
            'linkExpired' => $linkExpired,
            'userExist' => $userExist,
            'errorHeader' => $errorHeader,
            'errorMessage' => $errorMessage]);
    }

    public function resetPassword($key) {
        $params = base64_decode($key);
        $params = explode('&', $params);

        $hasError = true;
        $errorMessage = trans('messages.activation_try_again');

        if (count($params) == 3) {
            $userId = str_replace("userId=", "", $params[0]);
            $expiryDate = str_replace("expiryDate=", "", $params[1]);
            $activation_id = str_replace("activationId=", "", $params[2]);
        }

        if ($userId && !empty($userId)) {
            $userJson = null;
            $linkExpired = false;
            $user = UserService::getUserById($userId);

            if (!empty($user)) {
                if (strcmp($user->status, 'inactive') == 0 ||
                        strcmp($user->company_status, 'inactive') == 0) {
                    $errorMessage = trans('messages.account_inactive');

                    return view('inactiveuser');
                } else if (strcmp($user->forgot_pwd_request_id, $activation_id) == 0) {
                    $currTime = time();
                    if (strtotime($user->invitation_expired) >= $currTime) {
                        $hasError = false;
                        $userJson = array('email' => $user->email);
                    } else {
                        $linkExpired = true;
                        $errorMessage = trans('messages.reset_pwd_link_expired');
                    }
                }
            }
        }

        return view('resetpassword', ['userJson' => json_encode($userJson),
            'activationId' => $key,
            'hasError' => $hasError,
            'linkExpired' => $linkExpired,
            'errorMessage' => $errorMessage]);
    }

}
