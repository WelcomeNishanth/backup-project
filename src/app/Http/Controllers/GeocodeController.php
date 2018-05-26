<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ivory\GoogleMap\Service\Geocoder\GeocoderService;
use Http\Adapter\Guzzle6\Client;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Ivory\GoogleMap\Service\Geocoder\Request\GeocoderAddressRequest;
use Ivory\GoogleMap\Service\Geocoder\Request\GeocoderComponentType;
use Illuminate\Support\Facades\Validator;
use App\Services\Utils;

class GeocodeController extends Controller {

    public function getAddress(Request $request) {
        $data = $request->json()->all();

        if (!is_null($data) && !is_null($data['zipCode'])) {
            $apiUrl = "http://maps.googleapis.com/maps/api/geocode/json?address=" . $data['zipCode'];
            $resArray['zipCode'] = $data['zipCode'];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPGET, 1);

            if ($response['status'] == 'OK') {
                if (count($response['results']) > 0) {
                    foreach ($response['results'][0]['address_components'] as $addressComponent) {
                        switch ($addressComponent['types'][0]) {
                            case "locality" :
                                $resArray['city'] = $addressComponent['long_name'];
                                break;
                            case "administrative_area_level_1" :
                                $resArray['state'] = $addressComponent['long_name'];
                                break;
                            case "country" :
                                $resArray['country'] = $addressComponent['long_name'];
                                break;
                        }
                    }
                }
            }
        }

        return response()->json($resArray);
    }

    public function getAddressInfo(Request $request) {
        $zipcode = strtolower($request->header('zipcode'));
        $countries = $request->header('countries');

        $countriesArray = array();

        if (!empty($countries)) {
            $countries = str_replace(' ', '', $countries);
            $countriesArray = explode(",", $countries);
        }

        if (!isset($zipcode) || empty($zipcode)) {
            $errorMessage = trans('messages.missing_single_field', ['name' => 'Zip code']);
            return $this->logErrorAndSendResponse($errorMessage);
        }

        try {
            $geocoder = new GeocoderService(new Client(), new GuzzleMessageFactory());
            $key = getenv("GOOGLE_API_KEY");
            if (!empty($key)) {
                $geocoder->setKey($key);
            }

            $geocodeRequest = new GeocoderAddressRequest('');

            $geocodeRequest->setComponents([GeocoderComponentType::POSTAL_CODE => $zipcode]);
            $geocodeResponse = $geocoder->geocode($geocodeRequest);

            $resArray = NULL;
            if ($geocodeResponse->hasResults()) {
                $results = $geocodeResponse->getResults();

                if (count($results) > 0 && $results[0]->hasAddressComponents()) {
                    $isValidCountry = true;

                    $addressComponents = $results[0]->getAddressComponents();

                    $neighbourhood = null;
                    $city = null;
                    foreach ($addressComponents as $addressComponent) {
                        switch ($addressComponent->getTypes()[0]) {
                            case "postal_code" :
                                $resArray['zipCode'] = strtolower($addressComponent->getLongName());
                                break;
                            case "locality" :
                                $city = $addressComponent->getLongName();
                                break;
                            case "neighborhood" :
                                $neighbourhood = $addressComponent->getLongName();
                                break;
                            case "administrative_area_level_1" :
                                $resArray['state'] = $addressComponent->getLongName();
                                break;
                            case "country" :
                                $country = $addressComponent->getShortName();

                                if (count($countriesArray) == 0) {
                                    $resArray['country'] = $addressComponent->getShortName();
                                } else {
                                    if (in_array($country, $countriesArray)) {
                                        $resArray['country'] = $addressComponent->getShortName();
                                    } else {
                                        $isValidCountry = false;
                                        $resArray = NULL;  // Don't support other countries yet
                                        break 2; // Exit for loop
                                    }
                                }
                                break;
                        }
                    }
                    
                    $cities = array();
                    if ($isValidCountry && $results[0]->hasPostcodeLocalities()) {
                        $postalCodeCities = $results[0]->getPostcodeLocalities();
                        foreach ($postalCodeCities as $postalCodeCity) {
                            $cities[] = $postalCodeCity;
                        }
                    }

                    $citiesCount = count($cities);
                    if ($citiesCount > 0) {
                        if (!empty($neighbourhood)) {
                            $cities[] = $neighbourhood;
                        }
                        $resArray['cities'] = $cities;
                        $resArray['city'] = $cities[0];
                    } else {
                        if (!empty($neighbourhood) && !empty($city)) {
                            $resArray['cities'] = array($neighbourhood, $city);
                        } else if (!empty($neighbourhood)) {
                            $resArray['city'] = $neighbourhood;
                        } else {
                            $resArray['city'] = $city;
                        }
                    }
                }
            }

            if (empty($resArray)) {
                $errorMessage = trans('messages.incorrect_input', ['name' => 'Zip code']);
                return $this->logErrorAndSendResponse($errorMessage);
            }

            return $this->sendSuccessResponse($resArray);
        } catch (\Exception $exception) {
            $errorMessage = trans('messages.request_addres_failed');
            return $this->logExceptionAndSendResponse($exception, $errorMessage);
        }
    }

    public function validateAddress(Request $request) {
        $input_data = $request->json()->all();

        $countries = $request->header('countries');
        $countriesArray = array();

        if (!empty($countries)) {
            $countries = str_replace(' ', '', $countries);
            $countriesArray = explode(",", $countries);
        }

        if (isset($input_data)) {
            $rules = array(
                'country' => 'required',
                'state' => 'required',
                'city' => 'required',
                'zipCode' => 'required'
            );

            $validator = Validator::make($input_data, $rules);

            if ($validator->fails()) {
                $failureMsg = trans('messages.mandatory_head');

                $temp = $validator->errors()->all();
                $errorMessage = $failureMsg . implode(", ", $temp);

                return $this->logErrorAndSendResponse($errorMessage);
            }
        } else {
            $errorMessage = trans('messages.missing_payload');
            return $this->logErrorAndSendResponse($errorMessage);
        }

        $input_data['city'] = strtolower($input_data['city']);

        $validateResponse = Utils::validateAddressWithZip($input_data, $countriesArray);
        $statusCode = $validateResponse['statusCode'];

        return $this->sendJsonResponse($validateResponse, $statusCode);
    }

}
