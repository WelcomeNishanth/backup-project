<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use \Exception;
use App\Services\UserService;
use App\Services\CompanyService;
use App\Services\Utils;
use App\Common\AwsTools;
use App\Common\Util;
use \App\Services\CustomerService;
use Invoicing\Client\ApiException;

class CompanyController extends Controller {

    /**
     * Gateway Admin endpoint to create/update company
     * 
     * @param Request $request
     * @return type
     */
    public function upsert(Request $request) {
        $input_data = $request->json()->all();

        if (isset($input_data)) {
            $rules = array(
                'name' => 'required',
                'legal_name' => 'required'
            );

            $validator = Validator::make($input_data, $rules);

            if ($validator->fails()) {
                // return the missing input
                $failureMsg = trans('messages.mandatory_head');

                $temp = $validator->errors()->all();
                $errorMessage = $failureMsg . implode(", ", $temp);

                return $this->logErrorAndSendResponse($errorMessage);
            } else {
                $client = $request->header('client');
                $input_data['client'] = $client;

                return $this->upsertCompany($input_data, true);
            }
        } else {
            return $this->logErrorAndSendResponse(trans('messages.payload_missing'));
        }
    }

    /**
     * Gateway User endpoint to update his company profile
     * 
     * @param Request $request
     * @return type
     */
    public function update(Request $request) {
        $input_data = $request->json()->all();

        if (isset($input_data)) {
            $rules = array(
                'name' => 'required',
                'legal_name' => 'required'
            );

            $validator = Validator::make($input_data, $rules);

            if ($validator->fails()) {
                // return the missing input
                $failureMsg = trans('messages.mandatory_head');

                $temp = $validator->errors()->all();
                $errorMessage = $failureMsg . implode(", ", $temp);

                return $this->logErrorAndSendResponse($errorMessage);
            } else {
                $input_data['company_id'] = $_REQUEST['AUTHUSERDATA']['gw_company_id'];

                return $this->upsertCompany($input_data, false);
            }
        } else {
            return $this->logErrorAndSendResponse(trans('messages.payload_missing'));
        }
    }

    /**
     * Gateway User endpoint to get company profile
     * 
     * @param Request $request
     * @return type
     */
    public function index(Request $request) {
        $company_id = $_REQUEST['AUTHUSERDATA']['gw_company_id'];

        try {
            $result = CompanyService::getCompanyAsArrayById($company_id);

            if (!empty($result)) {
                $company_data = array();

                $company_data['name'] = $result['name'];
                $company_data['legal_name'] = $result['legal_name'];
                $company_data['signed_agreement'] = $result['signed_agreement'];
                $company_data['status'] = $result['status'];

                $billing_address_id = $result['billing_address_id'];
                $mailing_address_id = $result['mailing_address_id'];

                $address_ids = NULL;
                if (Utils::validateNumberField($billing_address_id)) {
                    $address_ids = strval($billing_address_id);
                }

                if (Utils::validateNumberField($mailing_address_id)) {
                    if ($mailing_address_id == $billing_address_id) {
                        $company_data['same_mailing_billing'] = true;
                    } else {
                        $company_data['same_mailing_billing'] = false;
                        if (empty($address_ids)) {
                            $address_ids = strval($mailing_address_id);
                        } else {
                            $address_ids = $address_ids . ',' . strval($mailing_address_id);
                        }
                    }
                }

                if (!empty($address_ids)) {
                    $address_results = CompanyService::getAddressesAsArrayByIds($address_ids);

                    if (count($address_results) > 0) {
                        foreach ($address_results as $address_result) {
                            $address_id = $address_result['address_id'];
                            if ($address_id == $billing_address_id) {
                                $company_data['billing_address'] = array(
                                    'address1' => $address_result['address1'],
                                    'city' => $address_result['city'],
                                    'state' => $address_result['state'],
                                    'country' => $address_result['country'],
                                    'postal_code' => $address_result['postal_code'],
                                    'country_code' => $address_result['phone_country_code'],
                                    'phone_number' => $address_result['phone_number'],
                                    'extension' => $address_result['phone_extension']
                                );
                            }
                            if ($address_id == $mailing_address_id) {
                                $company_data['mailing_address'] = array(
                                    'address1' => $address_result['address1'],
                                    'city' => $address_result['city'],
                                    'state' => $address_result['state'],
                                    'country' => $address_result['country'],
                                    'postal_code' => $address_result['postal_code'],
                                    'country_code' => $address_result['phone_country_code'],
                                    'phone_number' => $address_result['phone_number'],
                                    'extension' => $address_result['phone_extension']
                                );
                            }
                        }
                    }
                }

                return $this->sendSuccessResponse($company_data);
            } else {
                $errorMessage = trans('messages.company_not_found');
                return $this->logErrorAndSendResponse($errorMessage);
            }
        } catch (Exception $exception) {
            $errorMessage = trans('messages.company_req_failed');
            return $this->logExceptionAndSendResponse($exception, $errorMessage);
        }
    }

    /**
     * Update or insert company
     * 
     * @param type $companyData
     * @param type $isAdmin
     * @return type
     */
    private function upsertCompany($companyData, $isAdmin) {
        // Process the company data
        try {
            $billing_address_same = false;
            if (array_key_exists('same_mailing_billing', $companyData)) {
                $billing_address_same = $companyData['same_mailing_billing'];
            }

            $billing_address = array();
            $mailing_address = array();

            if (!$billing_address_same && array_key_exists('billing_address', $companyData)) {
                $billing_address = $companyData['billing_address'];
            }
            if (array_key_exists('mailing_address', $companyData)) {
                $mailing_address = $companyData['mailing_address'];
            }

            DB::beginTransaction();

            $mailingInfo = array();

            $mailing_address_id = NULL;
            $billing_address_id = NULL;
            $addressIds = NULL;
            if (Utils::validateNumberField($companyData, 'company_id')) {
                $addressIds = CompanyService::getAddressIdsByCompanyId($companyData['company_id']);
            } else if (Utils::validateStringField($companyData, 'sf_account_id')) {
                $addressIds = CompanyService::getAddressIdsBySfAccoundId($companyData['sf_account_id']);
            }

            if (!empty($addressIds)) {
                $mailing_address_id = $addressIds['mailing_address_id'];
                $billing_address_id = $addressIds['billing_address_id'];

                if (!$billing_address_same && $mailing_address_id == $billing_address_id) {
                    $billing_address_id = 0;
                } else if ($billing_address_same) {
                    $billing_address_id = $mailing_address_id;
                }
            }

            if (!empty($mailing_address)) {
//                if (Utils::validateStringField($mailing_address, 'postal_code') &&
//                        !empty($mailing_address['postal_code'])) {
//                    $validateResponse = $this->validateAddressWithZipCode($mailing_address);
//
//                    $statusCode = $validateResponse['statusCode'];
//                    if ($statusCode != 200) {
//                        $errorMessage = 'Mailing Address: ' . $validateResponse['message'];
//
//                        return $this->logErrorAndSendResponse($errorMessage, $validateResponse['devMsg'], true, $statusCode);
//                    } else {
//                        $mailing_address['state'] = $validateResponse['data']['stateFullName'];
//                        $mailing_address['country'] = $validateResponse['data']['country'];
//                    }
//                }

                if (Utils::validateNumberField($mailing_address_id)) {
                    $mailing_address['address_id'] = $mailing_address_id;
                    if (array_key_exists('sf_modified_timestamp', $companyData)) {
                        $mailing_address['sf_modified_timestamp'] = $companyData['sf_modified_timestamp'];
                    }
                    CompanyService::updateAddress($mailing_address, $mailingInfo);
                } else {
                    $mailing_address_id = CompanyService::createAddress($mailing_address, $mailingInfo);
                }
            }

            if (!$billing_address_same) {
                $billingInfo = array();

                if (!empty($billing_address)) {
//                    if (Utils::validateStringField($billing_address, 'postal_code') &&
//                            !empty($billing_address['postal_code'])) {
//                        $validateResponse = $this->validateAddressWithZipCode($billing_address);
//
//                        $statusCode = $validateResponse['statusCode'];
//                        if ($statusCode != 200) {
//                            $errorMessage = 'Billing Address: ' . $validateResponse['message'];
//
//                            return $this->logErrorAndSendResponse($errorMessage, $validateResponse['devMsg'], true, $statusCode);
//                        } else {
//                            $billing_address['state'] = $validateResponse['data']['stateFullName'];
//                            $billing_address['country'] = $validateResponse['data']['country'];
//                        }
//                    }

                    if (Utils::validateNumberField($billing_address_id)) {
                        $billing_address['address_id'] = $billing_address_id;
                        if (array_key_exists('sf_modified_timestamp', $companyData)) {
                            $billing_address['sf_modified_timestamp'] = $companyData['sf_modified_timestamp'];
                        }
                        CompanyService::updateAddress($billing_address, $billingInfo);
                    } else {
                        $billing_address_id = CompanyService::createAddress($billing_address, $billingInfo);
                    }
                }
            } else {
                $billing_address_id = $mailing_address_id;
            }

            $companyInfo = array();

            $company_id = NULL;
            $ops_status = '';

            Utils::verifyAndAdd($mailing_address_id, $companyInfo, NULL, 'mailing_address_id');
            Utils::verifyAndAdd($billing_address_id, $companyInfo, NULL, 'billing_address_id');

            if (Utils::validateNumberField($companyData, 'company_id')) {
                $company_id = $companyData['company_id'];

                CompanyService::updateCompany($companyData, $companyInfo, $isAdmin, 'company_id');

                $action = 'Update';
                $ops_status = 'updated';
            } else if (Utils::validateStringField($companyData, 'sf_account_id')) {
                $result = CompanyService::getCompanyAsArrayBySfAccountId($companyData['sf_account_id']);
                if (!empty($result)) {
                    $company_id = $result['company_id'];
                }

                if (Utils::validateNumberField($company_id)) {
                    CompanyService::updateCompany($companyData, $companyInfo, $isAdmin, 'sf_account_id');

                    $action = 'Update';
                    $ops_status = 'updated';
                } else {
                    $company_id = CompanyService::createCompany($companyData, $companyInfo);

                    $action = 'Create';
                    $ops_status = 'added';
                }
            } else {
                $company_id = CompanyService::createCompany($companyData, $companyInfo);

                $action = 'Create';
                $ops_status = 'added';
            }

            if (Utils::validateStringField($companyData, 'sf_account_id')) {
                UserService::updateCompanyIdBySfAccountId($companyData['sf_account_id'], $company_id);
            }

            $company_status = NULL;
            if (array_key_exists('status', $companyInfo)) {
                $company_status = $companyInfo['status'];
            } else {
                $result = CompanyService::getCompanyAsArrayById($company_id, ['status']);
                $company_status = $result['status'];
            }

            $companyObject = CompanyService::getCompanyById($company_id);
            if (strcmp($companyObject->status, 'approved') === 0) {
                CustomerService::upsert($companyObject);
            }

            if ($isAdmin) {
                UserService::sendActivationMailToUsers($company_id, $company_status);
            }

            //If is from SF, don't publish the events back
            if (!array_key_exists("client", $companyData) || strcasecmp($companyData["client"], "salesforce") != 0) {
                CompanyService::sendSns($companyObject, $action);
            }

            DB::commit();

            return $this->sendSuccessResponse(NULL, trans('messages.company_ins_update_staus_head') . $ops_status);
        } catch (ApiException $apiException) {
            DB::rollBack();
            $apiReponseObject = $apiException->getResponseBody();
            $devMsg = json_encode($apiReponseObject);
            $errorMessage = trans('messages.rfq_failed');
            return $this->logExceptionAndSendResponse($apiException, $errorMessage, $devMsg);
        } catch (\Exception $exception) {
            DB::rollBack();

            $errorMessage = trans('messages.company_procss_failed');

            return $this->logExceptionAndSendResponse($exception, $errorMessage);
        }
    }

    private function validateAddressWithZipCode($address_info) {
        $validateAddress = array();

        Utils::verifyAndAddLower($address_info, $validateAddress, 'city', 'city');
        Utils::verifyAndAddLower($address_info, $validateAddress, 'state', 'state');
        Utils::verifyAndAddLower($address_info, $validateAddress, 'country', 'country');
        Utils::verifyAndAddLower($address_info, $validateAddress, 'postal_code', 'zipCode');

        return Utils::validateAddressWithZip($validateAddress, [], true);
    }

    public function downloadAgreement() {
        $app_env = $_ENV['ENVIRONMENT'];

        if (!empty($app_env)) {
            if (!empty(getenv("AGREEMENT_DOWNLOAD_FILE_NAME"))) {
                $agreement_file = getenv("AGREEMENT_DOWNLOAD_FILE_NAME");
            } else {
                $agreement_file = 'Gateway_Terms_Of_Use.pdf';
            }

            $environmentPath = __DIR__ . '/../' . $agreement_file;

            $lastModified = 0;
            if (file_exists($environmentPath)) {
                $lastModified = filemtime($environmentPath);
            }

            $s3Client = AwsTools::getS3Client('us-west-2');
            $envS3Key = 'gateway-portal/ui/' . $app_env . '/' . getenv("AGREEMENT_S3_FILE_NAME");
            $envLastModifiedinS3 = AwsTools::getLastModified($s3Client, 'bd-auth-keys', $envS3Key);

            if ($lastModified == 0 || $lastModified < $envLastModifiedinS3) {
                $fileContent = AwsTools::getObjectFromS3($s3Client, 'bd-auth-keys', $envS3Key);
                Util::putFileContent($environmentPath, $fileContent);
            }

            $size = filesize($environmentPath);

            header("Content-Type:application/pdf");
            header("Content-Disposition:attachment");
            header("Content-length:" . $size);
            header("x-filename:" . $agreement_file);

            readfile($environmentPath);
        }
    }
}
