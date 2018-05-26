<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use \App\Common\AwsTools;
use App\Services\Utils;
use \PDO;

class CompanyService {

    public static function getCompanyAsArrayById($company_id, array $fieldsToGet = NULL) {
        $columnsToGet = "";
        if (!empty($fieldsToGet)) {
            $index = 0;
            foreach ($fieldsToGet as $fieldToGet) {
                if ($index > 0) {
                    $columnsToGet = $columnsToGet . ", ";
                }

                $columnsToGet = $columnsToGet . $fieldToGet;
                $index++;
            }
        }

        if (empty($columnsToGet)) {
            $columnsToGet = "*";
        }

        $companyData = NULL;

        DB::setFetchMode(PDO::FETCH_ASSOC);
        $results = DB::select('select ' . $columnsToGet . ' from gw_companies where company_id = ' . $company_id);
        DB::setFetchMode(PDO::FETCH_CLASS);

        if (count($results) > 0) {
            $companyData = $results[0];
        }

        return $companyData;
    }

    public static function getCompanyById($companyId) {
        $company = DB::table('gw_companies')->where('company_id', $companyId)->first();

        return $company;
    }

    public static function getCompanyAsArrayBySfAccountId($sfAccountId, array $fieldsToGet = NULL) {
        $columnsToGet = "";
        if (!empty($fieldsToGet)) {
            $index = 0;
            foreach ($fieldsToGet as $fieldToGet) {
                if ($index > 0) {
                    $columnsToGet = $columnsToGet . ", ";
                }

                $columnsToGet = $columnsToGet . $fieldToGet;
                $index++;
            }
        }

        if (empty($columnsToGet)) {
            $columnsToGet = "*";
        }

        $companyData = NULL;

        DB::setFetchMode(PDO::FETCH_ASSOC);
        $results = DB::select("select " . $columnsToGet . " from gw_companies where sf_account_id = '" . $sfAccountId . "'");
        DB::setFetchMode(PDO::FETCH_CLASS);
        if (count($results) > 0) {
            $companyData = $results[0];
        }

        return $companyData;
    }

    public static function getAddressIdsByCompanyId($company_id) {
        $addressIds = NULL;

        DB::setFetchMode(PDO::FETCH_ASSOC);
        $results = DB::select('select mailing_address_id, billing_address_id from gw_companies where company_id = ' . $company_id);
        DB::setFetchMode(PDO::FETCH_CLASS);

        if (count($results) > 0) {
            $companyData = $results[0];

            $addressIds = array(
                "mailing_address_id" => $companyData['mailing_address_id'],
                "billing_address_id" => $companyData['billing_address_id']
            );
        }

        return $addressIds;
    }

    public static function getAddressIdsBySfAccoundId($sf_account_id) {
        $addressIds = NULL;

        DB::setFetchMode(PDO::FETCH_ASSOC);
        $results = DB::select("select mailing_address_id, billing_address_id from gw_companies where sf_account_id = '" . $sf_account_id . "'");
        DB::setFetchMode(PDO::FETCH_CLASS);

        if (count($results) > 0) {
            $companyData = $results[0];

            $addressIds = array(
                "mailing_address_id" => $companyData['mailing_address_id'],
                "billing_address_id" => $companyData['billing_address_id']
            );
        }

        return $addressIds;
    }

    public static function getAddressAsArrayById($addressId) {
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $address = DB::table('gw_addresses')->where('address_id', $addressId)->first();
        DB::setFetchMode(PDO::FETCH_CLASS);

        return $address;
    }

    public static function getAddressById($addressId) {
        $address = DB::table('gw_addresses')->where('address_id', $addressId)->first();

        return $address;
    }

    public static function getAddressesAsArrayByIds($address_ids) {
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $address_results = DB::select('select address_id, address1, city, state, country, postal_code, '
                        . 'phone_country_code, phone_number, phone_extension '
                        . 'from gw_addresses where address_id in (' . $address_ids . ')');
        DB::setFetchMode(PDO::FETCH_CLASS);

        return $address_results;
    }

    public static function sendSns($companyObject, $action) {
        $messageData = array();

        $messageData['data'] = array("id" => $companyObject->company_id,
            "sfAccountId" => $companyObject->sf_account_id,
            "prospectId" => $companyObject->gw_prospect_id,
            "name" => $companyObject->name,
            "legal_name" => $companyObject->legal_name,
            "status" => $companyObject->status,
            "sfAccountStatus" => $companyObject->sf_account_status
        );

        if (!empty($companyObject->signed_agreement)) {
            $messageData['data']['signed_agreement'] = (bool) $companyObject->signed_agreement;
        } else {
            $messageData['data']['signed_agreement'] = false;
        }

        if (!empty($companyObject->agreement_signed_date)) {
            $messageData['data']['agreement_signed_date'] = strtotime($companyObject->agreement_signed_date);
        } else {
            $messageData['data']['agreement_signed_date'] = NULL;
        }

        $mailingAddressId = $companyObject->mailing_address_id;
        if (Utils::validateNumberField($mailingAddressId)) {
            $mailingAddressObject = self::getAddressById($mailingAddressId);
            $messageData['data']['mailingAddress'] = self::getAddressArray($mailingAddressObject);
        }

        $billingAddressId = $companyObject->billing_address_id;
        if (Utils::validateNumberField($billingAddressId)) {
            if ($billingAddressId == $mailingAddressId) {
                $messageData['data']['billingAddress'] = $messageData['data']['mailingAddress'];
            } else {
                $billingAddressObject = self::getAddressById($billingAddressId);
                $messageData['data']['billingAddress'] = self::getAddressArray($billingAddressObject);
            }
        }

        $messageData['JsonVersion'] = '1.0.0';
        $messageData['messageTime'] = \gmdate('c');

        $messageJson = json_encode($messageData);

        $lastModified = strtotime($companyObject->modified_timestamp);

        $properties = AwsTools::getMessageProperties('Company', $companyObject->company_id, $companyObject->legal_name, $action, $lastModified);

        $snsClient = AwsTools::getSnsClient(getenv('SNS_REGION'));
        $queueOrTopicName = getenv("COMPANIES_SNS_ARN");

        AwsTools::sendMessage($snsClient, $queueOrTopicName, $companyObject->company_id, $messageJson, null, $properties);
    }

    private static function getAddressArray($addressObject) {
        return array(
            "id" => $addressObject->address_id,
            "addressLine1" => $addressObject->address1,
            "city" => $addressObject->city,
            "state" => $addressObject->state,
            "country" => $addressObject->country,
            "postal_code" => $addressObject->postal_code,
            "phoneCountryCode" => $addressObject->phone_country_code,
            "phoneNumber" => $addressObject->phone_number,
            "phoneExtension" => $addressObject->phone_extension
        );
    }

    public static function createCompany(array $inputData, array $companyInfo) {
        Utils::verifyAndAdd($inputData, $companyInfo, 'company_type', 'company_type');
        Utils::verifyAndAdd($inputData, $companyInfo, 'company_subtype', 'company_subtype');

        Utils::verifyAndAdd($inputData, $companyInfo, 'name', 'name');
        Utils::verifyAndAdd($inputData, $companyInfo, 'legal_name', 'legal_name');

        Utils::verifyAndAdd($inputData, $companyInfo, 'signed_agreement', 'signed_agreement');
        if (Utils::validateBooleanField($companyInfo, 'signed_agreement')) {
            $companyInfo['agreement_signed_date'] = date("Y-m-d H:i:s", time());
        }

        Utils::verifyAndAdd($inputData, $companyInfo, 'status', 'status');
        Utils::verifyAndAdd($inputData, $companyInfo, 'sf_account_id', 'sf_account_id');
        Utils::verifyAndAdd($inputData, $companyInfo, 'sf_account_status', 'sf_account_status');
        if (Utils::validateNumberField($inputData, 'gw_prospect_id')) {
            $companyInfo['gw_prospect_id'] = $inputData['gw_prospect_id'];
        }

        $companyInfo['creation_timestamp'] = date("Y-m-d H:i:s", time());
        $companyInfo['modified_timestamp'] = date("Y-m-d H:i:s", time());

        return DB::table('gw_companies')->insertGetId($companyInfo);
    }

    public static function updateCompany(array $inputData, array $companyInfo, $isAdmin, $idField) {
        $keyId = $inputData[$idField];

        Utils::verifyAndAdd($inputData, $companyInfo, 'name', 'name');
        Utils::verifyAndAdd($inputData, $companyInfo, 'legal_name', 'legal_name');

        Utils::verifyAndAdd($inputData, $companyInfo, 'signed_agreement', 'signed_agreement');
        if (Utils::validateBooleanField($companyInfo, 'signed_agreement')) {
            $companyInfo['agreement_signed_date'] = date("Y-m-d H:i:s", time());
        }

        if ($isAdmin) {
            Utils::verifyAndAdd($inputData, $companyInfo, 'company_type', 'company_type');
            Utils::verifyAndAdd($inputData, $companyInfo, 'company_subtype', 'company_subtype');

            Utils::verifyAndAdd($inputData, $companyInfo, 'status', 'status');
            if (array_key_exists('status', $companyInfo)) {
                if (strcmp('approved', $companyInfo['status']) == 0) {
                    UserService::activateUsers($idField, $keyId);
                } else if (strcmp('inactive', $companyInfo['status']) == 0) {
                    UserService::inactivateUsers($idField, $keyId);
                }
            }

            Utils::verifyAndAdd($inputData, $companyInfo, 'sf_account_id', 'sf_account_id');
            Utils::verifyAndAdd($inputData, $companyInfo, 'sf_account_status', 'sf_account_status');
            if (Utils::validateNumberField($inputData, 'gw_prospect_id')) {
                $companyInfo['gw_prospect_id'] = $inputData['gw_prospect_id'];
            }
            Utils::verifyAndAdd($inputData, $companyInfo, 'ns_customer_id', 'ns_customer_id');
        }

        $companyInfo['modified_timestamp'] = date("Y-m-d H:i:s", time());

        if (Utils::validateNumberField($inputData, 'sf_modified_timestamp')) {
            $sf_last_modified_date = $inputData['sf_modified_timestamp'];
            $companyInfo['sf_modified_timestamp'] = $sf_last_modified_date;
            DB::table('gw_companies')->where($idField, $keyId)->where('sf_modified_timestamp', '<', $sf_last_modified_date)->update($companyInfo);
        } else {
            DB::table('gw_companies')->where($idField, $keyId)->update($companyInfo);
        }
    }

    public static function createAddress($input_data, $addressInfo) {
        Utils::verifyAndAdd($input_data, $addressInfo, 'address1', 'address1');
        Utils::verifyAndAdd($input_data, $addressInfo, 'city', 'city');
        Utils::verifyAndAdd($input_data, $addressInfo, 'state', 'state');
        Utils::verifyAndAdd($input_data, $addressInfo, 'country', 'country');
        Utils::verifyAndAdd($input_data, $addressInfo, 'postal_code', 'postal_code');
        Utils::verifyAndAdd($input_data, $addressInfo, 'country_code', 'phone_country_code');
        Utils::verifyAndAdd($input_data, $addressInfo, 'phone_number', 'phone_number');
        Utils::verifyAndAdd($input_data, $addressInfo, 'extension', 'phone_extension');

        if (!empty($addressInfo)) {
            $addressInfo['creation_timestamp'] = date("Y-m-d H:i:s", time());
            $addressInfo['modified_timestamp'] = date("Y-m-d H:i:s", time());

            return DB::table('gw_addresses')->insertGetId($addressInfo);
        }

        return NULL;
    }

    public static function updateAddress($input_data, $addressInfo) {
        Utils::verifyAndAdd($input_data, $addressInfo, 'address1', 'address1');
        Utils::verifyAndAdd($input_data, $addressInfo, 'city', 'city');
        Utils::verifyAndAdd($input_data, $addressInfo, 'state', 'state');
        Utils::verifyAndAdd($input_data, $addressInfo, 'country', 'country');
        Utils::verifyAndAdd($input_data, $addressInfo, 'postal_code', 'postal_code');
        Utils::verifyAndAdd($input_data, $addressInfo, 'country_code', 'phone_country_code');
        Utils::verifyAndAdd($input_data, $addressInfo, 'phone_number', 'phone_number');
        Utils::verifyAndAdd($input_data, $addressInfo, 'extension', 'phone_extension');

        $addressInfo['modified_timestamp'] = date("Y-m-d H:i:s", time());

        if (!empty($addressInfo)) {
            if (Utils::validateNumberField($input_data, 'sf_modified_timestamp')) {
                $sf_last_modified_date = $input_data['sf_modified_timestamp'];
                $addressInfo['sf_modified_timestamp'] = $sf_last_modified_date;
                DB::table('gw_addresses')->where('address_id', $input_data['address_id'])->where('sf_modified_timestamp', '<', $sf_last_modified_date)->update($addressInfo);
            } else {
                DB::table('gw_addresses')->where('address_id', $input_data['address_id'])->update($addressInfo);
            }
        }
    }
}
