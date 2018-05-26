<?php

namespace App\Services;

use Invoicing\Client\Api\CustomersApi;
use \Invoicing\Client\Configuration;
use \Invoicing\Client\ApiClient;
use Invoicing\Client\Model\ContactAddress;
use Invoicing\Client\Model\Customer;

class CustomerService {

    public static function upsert($companyObject) {
        $customer = new Customer();
        $customer->setCompanyName($companyObject->legal_name);

        $userDetails = UserService::getUserIdByCompanyId($companyObject->company_id, ['pending', 'awaiting activation', 'awaiting pwd reset', 'activated']);

        $contactName = '';
        if (!empty($userDetails)) {
            $contactName = UserService::getUserFullnameById($userDetails['user_id'], null);
            $customer->setEmail($userDetails['email']);

            if (!empty($userDetails['phone_number']) && strlen($userDetails['phone_number']) < 21 && strlen($userDetails['phone_number']) > 6) {
                $customer->setPhone($userDetails['phone_number']);
            }
        }

        $billing_address_id = $companyObject->billing_address_id;
        $mailing_address_id = $companyObject->mailing_address_id;

        $same_mailing_billing = false;
        if ($billing_address_id === $mailing_address_id) {
            $same_mailing_billing = true;
            $mailingAddressObject = CompanyService::getAddressAsArrayById($mailing_address_id);
            $customer->setBillingAddress(self::prepareAddressData($mailingAddressObject, $contactName));
            $customer->setMailingAddress(self::prepareAddressData($mailingAddressObject, $contactName));
        } else {
            $address_ids = null;
            if (!empty($billing_address_id)) {
                $address_ids = strval($billing_address_id);
            }

            if (empty($address_ids) && !empty($mailing_address_id)) {
                $address_ids = strval($mailing_address_id);
            } else if (!empty($mailing_address_id)) {
                $address_ids = $address_ids . ',' . strval($mailing_address_id);
            }

            if (!empty($address_ids)) {
                $address_results = CompanyService::getAddressesAsArrayByIds($address_ids);

                if (count($address_results) > 0) {
                    foreach ($address_results as $address_result) {
                        $address_id = $address_result['address_id'];
                        if ($address_id === $billing_address_id) {
                            $customer->setBillingAddress(self::prepareAddressData($address_result, $contactName));
                        }
                        if ($address_id === $mailing_address_id) {
                            $customer->setMailingAddress(self::prepareAddressData($address_result, $contactName));
                        }
                    }
                }
            }
        }
        
        $customer->setSameMailingBilling($same_mailing_billing);

        $api_uri = getenv("INVOICING_API_URL");
        $config = new Configuration();
        $config->setHost($api_uri);

        $apiClient = new ApiClient($config);

        $apiEndpoint = new CustomersApi($apiClient);
        if (empty($companyObject->ns_customer_id)) {
            // Create case
            $response = $apiEndpoint->customerCreate($customer);
            if (!empty($response) && !empty($response->data) && !empty($response->data->internalId)) {
                $ns_customer_id = $response->data->internalId;
                $updateInfo = array('ns_customer_id' => $ns_customer_id, 'company_id' => $companyObject->company_id);
                CompanyService::updateCompany($updateInfo, array(), true, 'company_id');
            }
        } else {
            // Update case 
            $customer_id = $companyObject->ns_customer_id;
            $response = $apiEndpoint->customerUpdate($customer_id, $customer);
        }
    }

    private static function prepareAddressData($addressData, $contactName = NULL) {
        $address = new ContactAddress();

        if (Utils::validateStringField($contactName)) {
            $address->setContact($contactName);
        }

        if (Utils::validateStringField($addressData, 'phone_number')) {
            if (!empty($addressData['phone_number']) && strlen($addressData['phone_number']) < 21 && strlen($addressData['phone_number']) > 6) {
                $address->setPhone($addressData['phone_number']);
            }
        }

        if (Utils::validateStringField($addressData, 'address1')) {
            $address->setAddressLine1($addressData['address1']);
        }

        if (Utils::validateStringField($addressData, 'city')) {
            $address->setCity($addressData['city']);
        }

        if (Utils::validateStringField($addressData, 'state')) {
            $address->setState($addressData['state']);
        }

        if (Utils::validateStringField($addressData, 'country')) {
            $address->setCountry($addressData['country']);
        }

        if (Utils::validateStringField($addressData, 'postal_code')) {
            $address->setZipCode($addressData['postal_code']);
        }

        return $address;
    }

}
