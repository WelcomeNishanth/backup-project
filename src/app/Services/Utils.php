<?php

namespace App\Services;

use Ivory\GoogleMap\Service\Geocoder\GeocoderService;
use Http\Adapter\Guzzle6\Client;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Ivory\GoogleMap\Service\Geocoder\Request\GeocoderAddressRequest;
use Ivory\GoogleMap\Service\Geocoder\Request\GeocoderComponentType;
use Gateway\Client\Configuration;
use Gateway\Client\ApiClient;
use Gateway\Client\ApiException;
use Gateway\Client\Api\DensityApi;
use \App\Services\MiddlewareFactory;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use App\Services\UserService;

class Utils {

    public static function verifyAndAdd($inputVal, &$outputArray, $fieldToAdd, $fieldName) {
        if (gettype($inputVal) == 'array') {
            if (array_key_exists($fieldToAdd, $inputVal) && !is_null($inputVal[$fieldToAdd])) {
                $outputArray[$fieldName] = $inputVal[$fieldToAdd];
            }
        } else if (!is_null($inputVal)) {
            $outputArray[$fieldName] = $inputVal;
        }
    }

    public static function verifyAndAddLower($inputVal, &$outputArray, $fieldToAdd, $fieldName) {
        if (gettype($inputVal) == 'array') {
            if (array_key_exists($fieldToAdd, $inputVal) && !is_null($inputVal[$fieldToAdd])) {
                $outputArray[$fieldName] = strtolower($inputVal[$fieldToAdd]);
            }
        } else if (!is_null($inputVal)) {
            $outputArray[$fieldName] = strtolower($inputVal);
        }
    }

    public static function validateNumberField($inputData, $arrayKey = NULL) {
        if (gettype($inputData) == 'array') {
            if (!empty($inputData) && array_key_exists($arrayKey, $inputData)) {
                $arrayVal = $inputData[$arrayKey];
                if (!is_null($arrayVal) && $arrayVal > 0) {
                    return true;
                }
            }
        } else {
            if (!is_null($inputData) && $inputData > 0) {
                return true;
            }
        }

        return false;
    }

    public static function validateBooleanField($inputData, $arrayKey = NULL) {
        $val = null;

        if (gettype($inputData) == 'array') {
            if (!empty($inputData) && array_key_exists($arrayKey, $inputData)) {
                $val = $inputData[$arrayKey];
            }
        } else {
            $val = $inputData;
        }

        if (!is_null($val)) {
            if (is_bool($val)) {
                return $val;
            } else {
                return boolval($val);
            }
        }

        return false;
    }

    public static function validateStringField($inputData, $arrayKey = NULL) {
        if (gettype($inputData) == 'array') {
            if (!empty($inputData) && array_key_exists($arrayKey, $inputData)) {
                $arrayVal = $inputData[$arrayKey];
                if (!is_null($arrayVal) && strcmp($arrayVal, '0') != 0) {
                    return true;
                }
            }
        } else {
            if (!is_null($inputData) && strcmp($inputData, '0') != 0) {
                return true;
            }
        }

        return false;
    }

    public static function check_keys_in_array(array $inputArray, array $keysToCheck) {
        if (!empty($inputArray)) {
            if (!empty($keysToCheck)) {
                foreach ($keysToCheck as $keyToCheck) {
                    if (!array_key_exists($keyToCheck, $inputArray)) {
                        return false;
                    }
                }
            }

            return true;
        }

        return false;
    }

    public static function validateAddressWithZip(array $input_data, array $countriesArray, $ignoreCity = false) {
        $zipCodeTemp = preg_replace('/\s+/', '', $input_data['zipCode']);
        $zipcode = $zipCodeTemp;
        $input_data['zipCode'] = $zipcode;

        try {
            $geocoder = new GeocoderService(new Client(), new GuzzleMessageFactory());
            $key = getenv("GOOGLE_API_KEY");
            if (!empty($key)) {
                $geocoder->setKey($key);
            }

            $geocodeRequest = new GeocoderAddressRequest('');
            $geocodeRequest->setComponents([GeocoderComponentType::POSTAL_CODE => $zipcode]);
            $geocodeResponse = $geocoder->geocode($geocodeRequest);

            if ($geocodeResponse->hasResults()) {
                $resArray = array();
                $compareLongArray = array();
                $compareShortArray = array();

                $results = $geocodeResponse->getResults();

                if (count($results) > 0 && $results[0]->hasAddressComponents()) {
                    $addressComponents = $results[0]->getAddressComponents();

                    foreach ($addressComponents as $addressComponent) {
                        switch ($addressComponent->getTypes()[0]) {
                            case "postal_code" :
                                $resArray['zipCode'] = $addressComponent->getLongName();

                                $longName = preg_replace('/\s+/', '', $addressComponent->getLongName());

                                $compareLongArray['zipCode'] = strtolower($longName);
                                $compareShortArray['zipCode'] = strtolower($longName);

                                break;
                            case "locality" :
                                $resArray['city'] = $addressComponent->getLongName();

                                $compareLongArray['city'] = strtolower($addressComponent->getLongName());
                                $compareShortArray['city'] = strtolower($addressComponent->getShortName());

                                break;
                            case "neighborhood" :
                                if (!array_key_exists('city', $resArray) || empty($resArray['city'])) {
                                    $resArray['city'] = $addressComponent->getLongName();

                                    $compareLongArray['city'] = strtolower($addressComponent->getLongName());
                                    $compareShortArray['city'] = strtolower($addressComponent->getShortName());
                                }

                                break;
                            case "administrative_area_level_1" :
                                $resArray['state'] = $addressComponent->getShortName();
                                $resArray['stateFullName'] = $addressComponent->getLongName();

                                $compareLongArray['state'] = strtolower($addressComponent->getLongName());
                                $compareShortArray['state'] = strtolower($addressComponent->getShortName());

                                break;
                            case "country" :
                                $country = $addressComponent->getShortName();
                                $countryFullName = $addressComponent->getLongName();

                                if (empty($countriesArray) || count($countriesArray) == 0 ||
                                        in_array($country, $countriesArray)) {
                                    $resArray['country'] = $country;
                                    $resArray['countryFullName'] = $countryFullName;

                                    $compareLongArray['country'] = strtolower($countryFullName);
                                    $compareShortArray['country'] = strtolower($country);
                                } else {
                                    $resArray = NULL;  // Don't support other countries yet

                                    $compareLongArray = NULL;
                                    $compareShortArray = NULL;

                                    break 2; // Exit for loop
                                }
                        }
                    }
                }

                if (empty($resArray)) {
                    $errorMessage = trans('messages.incorrect_input', ['name' => 'Zip code']);
                    $out = array('statusCode' => 400, 'success' => false, 'message' => $errorMessage, 'devMsg' => $errorMessage);

                    return $out;
                }

                if (!$ignoreCity) {
                    if ($results[0]->hasPostcodeLocalities()) {
                        $possibleCities = $results[0]->getPostcodeLocalities();

                        if (!empty($possibleCities)) {
                            $convertedArray = array();
                            foreach ($possibleCities as $value) {
                                $convertedArray[] = strtolower($value);
                            }

                            if (in_array($input_data['city'], $convertedArray)) {
                                unset($input_data['city']);
                                unset($resArray['city']);
                                unset($compareLongArray['city']);
                                unset($compareShortArray['city']);
                            }
                        }
                    }
                } else {
                    unset($input_data['city']);
                    unset($resArray['city']);
                    unset($compareLongArray['city']);
                    unset($compareShortArray['city']);
                }

                $failedKeys = self::diff_address($input_data, $compareLongArray, $compareShortArray, $ignoreCity);

                $fieldErrMsg = NULL;
                if (!empty($failedKeys) && count($failedKeys) > 0) {
                    $tempMsg = implode(",", $failedKeys);
                    $errorMessage = trans('messages.incorrect_input', ['name' => $tempMsg]);
                    $fieldErrMsg = $errorMessage;
                }

                if (is_null($fieldErrMsg)) {
                    $validAddress = trans('messages.valid_address');
                    $out = array('statusCode' => 200, 'success' => true, 'message' => $validAddress, 'data' => $resArray);

                    return $out;
                } else {
                    $out = array('statusCode' => 400, 'success' => false, 'message' => $fieldErrMsg, 'devMsg' => $fieldErrMsg);

                    return $out;
                }
            } else {
                $errorMessage = trans('messages.incorrect_input', ['name' => 'Zip code']);
                $out = array('statusCode' => 400, 'success' => false, 'message' => $errorMessage, 'devMsg' => $errorMessage);

                return $out;
            }
        } catch (\Exception $e) {
            $message = trans('messages.incorrect_address');

            return array(
                'statusCode' => 500,
                'success' => false,
                'message' => $message,
                'devMsg' => $e->getMessage() . ":" . $e->getTraceAsString(),
            );
        }
    }

    private static function diff_address($input_data, $compareLongArray, $compareShortArray, $ignoreCity) {
        $components = ['zipCode', 'state', 'country'];

        if (!$ignoreCity) {
            $components[] = 'city';
        }

        $failedKeys = array();
        foreach ($components as $component) {
            $matched = false;

            if (array_key_exists($component, $input_data) &&
                    array_key_exists($component, $compareLongArray)) {
                if (strcmp($input_data[$component], $compareLongArray[$component]) === 0) {
                    $matched = true;
                } else if (strpos($input_data[$component], $compareLongArray[$component]) === 0) {
                    $matched = true;
                }
            }

            if (!$matched && array_key_exists($component, $input_data) &&
                    array_key_exists($component, $compareShortArray)) {
                if (strcmp($input_data[$component], $compareShortArray[$component]) === 0) {
                    $matched = true;
                } else if (strpos($input_data[$component], $compareShortArray[$component]) === 0) {
                    $matched = true;
                }
            }

            if (!$matched && !array_key_exists($component, $input_data) &&
                    !array_key_exists($component, $compareLongArray) &&
                    !array_key_exists($component, $compareShortArray)) {
                $matched = true;
            }

            if (!$matched) {
                $failedKeys[] = $component;
            }
        }

        return $failedKeys;
    }

    public static function getGatewayApiClient() {
        $authType = getenv("GATEWAY_API_KEY_OR_USER_PWD");

        if (empty($authType)) {
            $authType = "USER_PWD";
        }

        $config = new Configuration();
        
        if (strcmp($authType, 'API_KEY') === 0) {
            $api_uri = getenv("GATEWAY_API_AZURE_V3_URL");

            if(isset($_REQUEST['AUTHUSERDATA']) && isset($_REQUEST['AUTHUSERDATA']['gw_user_id'])) {
                $user_id = $_REQUEST['AUTHUSERDATA']['gw_user_id'];
                $api_key = UserService::getApiKeyByUserId($user_id);
                if (empty($api_key)) {
                    $api_key = getenv('OCP_APIM_SUBSCRIPTION_KEY');
                }
            } else {
                $api_key = getenv('OCP_APIM_SUBSCRIPTION_KEY');
            }            
            
            $config->addDefaultHeader("Ocp-Apim-Subscription-Key", $api_key);
        } else {
            $api_uri = getenv("GATEWAY_API_V3_URL");

            $headerValue = "Basic " . base64_encode(getenv("GW_API_V3_USERNAME") . ":" . getenv("GW_API_V3_PASSWORD"));
            $config->addDefaultHeader("Authorization", $headerValue);
        }
        
        $config->setHost($api_uri);
        $config->addDefaultHeader("bd-operation-mode", getenv("BD_OPERATION_MODE"));

        $apiClient = new ApiClient($config);

        return $apiClient;
    }

    public static function calculateCCode(array $productSpec) {
        try {
            $jsonPayload = json_encode($productSpec);

            $estimates = new DensityApi(self::getGatewayApiClient());

            return $estimates->createdensity($jsonPayload);
        } catch (ApiException $e) {
            $message = trans('messages.product_not_found');
            return array(
                'statusCode' => 500,
                'success' => false,
                'message' => $message,
                'devMsg' => $e->getResponseBody(),
            );
        } catch (\Exception $e) {
            $message = trans('messages.product_not_found');
            return array(
                'statusCode' => 500,
                'success' => false,
                'message' => $message,
                'devMsg' => $e->getMessage() . ":" . $e->getTraceAsString(),
            );
        }
    }

    public static function getGuzzleOptions() {
        $handlerStack = HandlerStack::create(new CurlHandler());

        $middlewareFactory = new MiddlewareFactory();
        $handlerStack->push($middlewareFactory->retry(true));

        return array('handler' => $handlerStack);
    }

    public static function getAddressLongName(array $input_data) {
        $zipcode = $input_data['postalCode'];

        try {
            $geocoder = new GeocoderService(new Client(), new GuzzleMessageFactory());

            $geocodeRequest = new GeocoderAddressRequest('');
            $geocodeRequest->setComponents(
                    [
                        GeocoderComponentType::POSTAL_CODE => $zipcode,
                    ]
            );

            $geocodeResponse = $geocoder->geocode($geocodeRequest);

            if ($geocodeResponse->hasResults()) {
                $resArray = array();

                $results = $geocodeResponse->getResults();
                $resArray['zipCode'] = $zipcode;

                if (count($results) > 0 && $results[0]->hasAddressComponents()) {
                    $addressComponents = $results[0]->getAddressComponents();

                    foreach ($addressComponents as $addressComponent) {
                        switch ($addressComponent->getTypes()[0]) {
                            case "locality" :
                                $resArray['city'] = $addressComponent->getLongName();
                                break;
                            case "neighborhood" :
                                if (!array_key_exists('city', $resArray) || empty($resArray['city'])) {
                                    $resArray['city'] = $addressComponent->getLongName();
                                }
                                break;
                            case "administrative_area_level_1" :
                                $resArray['state'] = $addressComponent->getLongName();
                                break;
                            case "country" :
                                $resArray['country'] = $addressComponent->getLongName();
                                break;
                        }
                    }
                }

                return $resArray;
            }
        } catch (\Exception $e) {
            $message = trans('messages.incorrect_address');

            return array(
                'statusCode' => 500,
                'success' => false,
                'message' => $message,
                'devMsg' => $e->getMessage() . ":" . $e->getTraceAsString(),
            );
        }
    }

    public static function converToISOTime($timeToConvert = null) {
        $tz = @date_default_timezone_get();
        @date_default_timezone_set('UTC');

        if (is_null($timeToConvert)) {
            $timeToConvert = time();
        }

        $returnValue = str_replace('+00:00', '.0000000Z', @date('c', $timeToConvert));

        @date_default_timezone_set($tz);

        return $returnValue;
    }

}
