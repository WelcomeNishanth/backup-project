<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use \Exception;
use \PDO;
use \App\Notification\EmailNotifier;
use \App\Services\Utils;
use \App\Services\UserService;
use Gateway\Client\ApiException;
use Gateway\Client\Api\EstimatesApi;
use Gateway\Client\Api\DeliveriesApi;
use App\Common\AwsTools;

class QuoteController extends Controller {

    public function index(Request $request) {
        
    }

    /**
     * Get the stats of quotes and deliveries as in counts in various states
     * 
     * @param Request $request
     * @return type 
     */
    public function stats(Request $request) {
        $user_id = $_REQUEST['AUTHUSERDATA']['gw_user_id'];
        $company_id = $_REQUEST['AUTHUSERDATA']['gw_company_id'];

        try {
            // Call the DB on that user id
            DB::setFetchMode(PDO::FETCH_ASSOC);

            $query = '';
            if (UserService::isSuperUser()) {
                $query = 'select count(*) as count, status from gw_estimates where company_id = '
                        . $company_id . ' and expiry_date > curtime() group by status';
            } else {
                $query = 'select count(*) as count, status from gw_estimates where user_id = '
                        . $user_id . ' and expiry_date > curtime() group by status';
            }

            $results = DB::select($query);

            DB::setFetchMode(PDO::FETCH_CLASS);

            if (count($results) > 0) {
                $status_data = array();

                foreach ($results as $result) {
                    $status_data[$result['status']] = $result['count'];
                }

                $stats_data = array();
                $stats_data['quotes'] = $status_data;
                $stats_data['deliveries'] = array(
                    "pending" => 0,
                    "completed" => 0
                );

                return $this->sendSuccessResponse($stats_data);
            } else {
                $errorMessage = trans('messages.no_user_data');

                return $this->logErrorAndSendResponse($errorMessage);
            }
        } catch (\Exception $exception) {
            $errorMessage = trans('messages.rfq_stats_failed');

            return $this->logExceptionAndSendResponse($exception, $errorMessage);
        }
    }

    /**
     * Get the list of quotes
     * 
     * @param Request $request
     * @return type
     */
    public function quotes(Request $request) {
        $company_id = $_REQUEST['AUTHUSERDATA']['gw_company_id'];
        $user_id = $_REQUEST['AUTHUSERDATA']['gw_user_id'];

        try {
            // Call the DB on that user id
            DB::setFetchMode(PDO::FETCH_ASSOC);

            $data = $request->json()->all();
            $input_data = $data['request'];

            $page_size = getenv("PAGE_SIZE");

            if (array_key_exists('limit', $input_data)) {
                $page_size = $input_data['limit'];
            }

            $search_id = null;
            if (array_key_exists('searchId', $input_data)) {
                $search_id = $input_data['searchId'];
            }

            $offset = 0;
            if (array_key_exists('offset', $input_data)) {
                $offset = $input_data['offset'];
                if (empty($offset)) {
                    $offset = 0;
                }
            }

            $sort_key = null;
            if (array_key_exists('sortKey', $input_data)) {
                $sort_key = $input_data['sortKey'];
            } else {
                $sort_key = 'created_time';
            }

            $sort_by = null;
            if (array_key_exists('sortBy', $input_data)) {
                $sort_by = $input_data['sortBy'];
            } else {
                $sort_by = 'asc';
            }

            $isSuperUser = UserService::isSuperUser();
            $totalCount = null;
            $totalPages = null;
            if (empty($search_id) || empty($offset)) {
                if ($isSuperUser) {
                    $countQuery = 'select count(1) from gw_estimates where company_id = ' . $company_id;
                } else {
                    $countQuery = 'select count(1) from gw_estimates where user_id = ' . $user_id;
                }

                $estimate_results = DB::select($countQuery);
                if (count($estimate_results) > 0) {
                    $totalCount = $estimate_results[0]['count(1)'];
                    $totalPages = ceil($totalCount / $page_size);
                }
            }

            $query = '';
            if ($isSuperUser) {
                $query = 'select user_id, estimate_id, status, accepted_quote_id, accepted_user_id, accepted_delivery_type, '
                        . 'expiry_date, requested_date, effective_date, quotes_data from gw_estimates '
                        . 'where company_id = ' . $company_id;
            } else {
                $query = 'select user_id, estimate_id, status, accepted_quote_id, accepted_user_id, accepted_delivery_type, '
                        . 'expiry_date, requested_date, effective_date, quotes_data from gw_estimates '
                        . 'where user_id = ' . $user_id;
            }

            if (!empty($search_id)) {
                $query .= ' and unix_timestamp(updated_time) <= ' . $search_id;
            } else {
                $search_id = time();
            }

            $query .= ' order by ' . $sort_key . ' ' . $sort_by . ' limit ' . $offset . " ," . $page_size;

            $results = DB::select($query);

            DB::setFetchMode(PDO::FETCH_CLASS);

            if (count($results) > 0) {
                $myquotes_data = array();
                $expired_estimate_ids = array();
                $userMap = array();

                foreach ($results as $result) {
                    $quote_data = $this->getQuoteData($result, $userMap);

                    if ($quote_data['run_expiry'] == true) {
                        unset($quote_data['run_expiry']);
                        $expired_estimate_ids[] = $quote_data['estimate_id'];
                    }

                    $myquotes_data[] = $quote_data;
                }

                if (!empty($expired_estimate_ids)) {
                    $this->updateExpiredQuotes($expired_estimate_ids, 'expired');
                }

                return $this->sendSuccessResponse(array('quotes' => $myquotes_data,
                            'searchId' => $search_id,
                            'offset' => $offset,
                            'pageSize' => $page_size,
                            'totalCount' => $totalCount,
                            'totalPages' => $totalPages
                ));
            } else {
                $errorMessage = trans('messages.no_rfq_user');
                return $this->logErrorAndSendResponse($errorMessage);
            }
        } catch (\Exception $exception) {
            $errorMessage = trans('messages.rfq_stats_failed');
            return $this->logExceptionAndSendResponse($exception, $errorMessage);
        }
    }

    private function getQuoteData($estimateData, $userMap = NULL, $ignoreValueDelivery = false) {
        $quote_data_json = $estimateData['quotes_data'];

        $quote_data = json_decode($quote_data_json, true);

        $estimate_id = $estimateData['estimate_id'];

        $status = $estimateData['status'];
        $effective_date = $estimateData['effective_date'];
        $expiry_date = strtotime($estimateData['expiry_date']);
        $possibleDeliveries = $quote_data['possibleDeliveries'];

        $options = array();
        $rate_quoted = 0;
        $run_expiry = false;
        if (strcmp($status, 'pending') == 0 || strcmp($status, 'expired') == 0) {
            if ($expiry_date != 0 && $expiry_date <= time()) {
                $status = 'expired';
                $run_expiry = true;
            } else {
                if (count($possibleDeliveries) > 0) {
                    $non_satellite_deliveries = $this->getNonSatelliteRatingQuotes($possibleDeliveries);

                    $lowestQuoteRate = null;
                    if (!empty($non_satellite_deliveries) && count($non_satellite_deliveries) > 0) {
                        $lowestQuote = $this->getLowestQuoteDetails($non_satellite_deliveries);
                        $lowestQuoteRate = $lowestQuote['quote_rate'];

                        $fastestQuote = $this->getFastestQuoteDetails($non_satellite_deliveries);

                        if (strcmp($lowestQuote['quote_id'], $fastestQuote['quote_id']) === 0) {
                            $lowestQuote["type"] = 4;
                            $lowestQuote["name"] = trans('messages.standard_options');

                            $options[] = $lowestQuote;
                        } else {
                            $options[] = $lowestQuote;
                            $options[] = $fastestQuote;
                        }
                    }

                    if (!$ignoreValueDelivery || count($options) === 0) {
                        $satellite_deliveries = $this->getSatelliteRatingQuotes($possibleDeliveries);

                        if (!empty($satellite_deliveries) && count($satellite_deliveries) > 0) {
                            $lowestSatelliteOption = $this->getLowestSatelliteQuoteDetails($satellite_deliveries);
                            $fastestSatelliteOption = null;
                            /*
                              $fastestSatelliteOption = $this->getFastestSatelliteQuoteDetails($satellite_deliveries);

                              if (strcmp($lowestSatelliteOption['quote_id'], $fastestSatelliteOption['quote_id']) == 0) {
                              $lowestSatelliteOption["type"] = 6;
                              $lowestSatelliteOption["name"] = trans('messages.satellite_options');

                              $fastestSatelliteOption = null;
                              } */

                            if (!empty($lowestSatelliteOption) && (is_null($lowestQuoteRate) || $lowestSatelliteOption['quote_rate'] < $lowestQuoteRate)) {
                                $options[] = $lowestSatelliteOption;
                            }

                            if (!empty($fastestSatelliteOption)) {
                                if ($fastestSatelliteOption['leadTime'] < $fastestQuote['leadTime'] ||
                                        $fastestSatelliteOption['quote_rate'] < $fastestQuote['quote_rate']) {
                                    $options[] = $fastestSatelliteOption;
                                }
                            }
                        }
                    }

                    $countOfOptions = count($options);
                    if ($countOfOptions > 1) {
                        usort($options, function($option1, $option2) {
                            if (!isset($option1['quote_rate']) || empty($option2['quote_rate'])) {
                                return 1;
                            } else if (!isset($option2['quote_rate']) || empty($option1['quote_rate'])) {
                                return -1;
                            } else if ($option1['quote_rate'] == $option2['quote_rate']) {
                                return 0;
                            } elseif ($option1['quote_rate'] > $option2['quote_rate']) {
                                return 1;
                            } else {
                                return -1;
                            }
                        });

                        $lowest_rate = number_format($options[0]['quote_rate'], 2, '.', ',');
                        $highest_rate = number_format(end($options)['quote_rate'], 2, '.', ',');

                        $rate_quoted = '$' . $lowest_rate . '-$' . $highest_rate;
                    } else if ($countOfOptions == 1) {
                        $rate_quoted = '$' . number_format($options[0]['quote_rate'], 2, '.', ',');
                    }
                } else {
                    $status = 'manual quote';
                }
            }
        } else if (strcmp($status, 'accepted') == 0) {
            $accepted_quote_id = $estimateData['accepted_quote_id'];

            if (!empty($accepted_quote_id) || strcmp($accepted_quote_id, '0') === 0) {
                $options[] = $this->getAcceptedQuoteDetailsByQuoteId($possibleDeliveries, $accepted_quote_id);

                if (!empty($options) && is_array($options[0])) {
                    $options[0]['accepted_quote_type'] = $estimateData['accepted_delivery_type'];
                    if (array_key_exists('quote_rate', $options[0])) {
                        $rate_quoted = '$' . number_format($options[0]['quote_rate'], 2, '.', ',');
                    }
                }
            }
        } else if (strcmp($status, 'manual') == 0) {
            $status = 'manual quote';
        }

        unset($quote_data['possibleDeliveries']);

        if (isset($quote_data['originLocation'])) {
            $originLocationTypeValue = $this->getLocationType($quote_data['originLocation']);
            $quote_data['originLocation']['information']['locationType']['value'] = $originLocationTypeValue;
            $quote_data['originLocation']['information']['locationType']['displayName'] = $this->getLocationName($originLocationTypeValue);
        }
        if (isset($quote_data['destinationLocation'])) {
            $destionationLocationTypeValue = $this->getLocationType($quote_data['destinationLocation']);
            $quote_data['destinationLocation']['information']['locationType']['value'] = $destionationLocationTypeValue;
            $quote_data['destinationLocation']['information']['locationType']['displayName'] = $this->getLocationName($destionationLocationTypeValue);
        }

        unset($quote_data['methodologyErrors']);

        $user_id = $estimateData['user_id'];
        $fullName = UserService::getUserFullnameById($user_id, $userMap);

        $response_data = array(
            'estimate_id' => $estimate_id,
            'request' => $quote_data,
            'requested_date' => date("F j, Y", strtotime($estimateData['requested_date'])),
            'rate_quoted' => $rate_quoted,
            'options' => $options,
            'status' => $status,
            'run_expiry' => $run_expiry,
            'requested_user' => $fullName
        );

        $accepted_user_id = array_key_exists('accepted_user_id', $estimateData) ? $estimateData['accepted_user_id'] : NULL;
        if (!empty($accepted_user_id)) {
            $response_data['accepted_user'] = UserService::getUserFullnameById($accepted_user_id, $userMap);
        }

        if (!empty($effective_date)) {
            $response_data['effective_date'] = date("F j, Y", strtotime($effective_date));
        }

        if ($expiry_date != 0) {
            $response_data['expiry_date'] = date("F j, Y", $expiry_date);
        }

        return $response_data;
    }

    private function getLocationType($location) {
        $locationType = null;

        if (!empty($location) && array_key_exists('information', $location) &&
                array_key_exists('locationType', $location['information']) && array_key_exists('value', $location['information']['locationType'])) {
            $value = $location['information']['locationType']['value'];
            $dataType = gettype($value);

            if ($dataType === 'array' && count($value) > 0) {
                $locationType = $value[0];
                if (strcmp("commercial", $locationType) == 0) {
                    $dockType = $this->getDockType($location);
                    if (strcmp($dockType, "no-dock") == 0) {
                        $locationType = "commercial-nodock";
                    } else if (strcmp($dockType, "has-dock") == 0) {
                        $locationType = "commercial-hasdock";
                    } else {
                        $locationType = "commercial-hasdock";
                    }
                }
            } else if ($dataType === 'string') {
                $locationType = $value;
            }
        }

        return $locationType;
    }

    private function getLocationName($shipment_location_type) {
        $locationType = null;
        if (strcmp($shipment_location_type, "commercial-nodock") == 0) {
            $locationType = "Commercial (No Dock)";
        } else if (strcmp($shipment_location_type, "commercial-hasdock") == 0) {
            $locationType = "Commercial (With Dock)";
        } else {
            $locationType = $shipment_location_type;
        }

        return $locationType;
    }

    private function getDockType($location) {
        $dockType = null;

        if (!empty($location) && array_key_exists('information', $location) &&
                array_key_exists('dock', $location['information']) && array_key_exists('value', $location['information']['dock'])) {
            $value = $location['information']['dock']['value'];
            $dataType = gettype($value);
            if ($dataType === 'array' && count($value) > 0) {
                $dockType = $value[0];
            } else if ($dataType === 'string') {
                $dockType = $value;
            }
        }

        return $dockType;
    }

    private function getSatelliteRatingQuotes(array $possibleDeliveries) {
        $satellite_deliveries = array_filter($possibleDeliveries, function ($posDelivery) {
            if (array_key_exists('methodology', $posDelivery) &&
                    (strcmp('satellite-ltl', $posDelivery['methodology']) == 0 || strcmp('network-ltl', $posDelivery['methodology']) == 0)) {
                if (!isset($posDelivery['error'])) {
                    return true;
                }
            }

            return false;
        });

        return $satellite_deliveries;
    }

    private function getNonSatelliteRatingQuotes(array $possibleDeliveries) {
        $non_satellite_deliveries = array_filter($possibleDeliveries, function ($posDelivery) {
            if (array_key_exists('methodology', $posDelivery) &&
                    (strcmp('direct-ltl', $posDelivery['methodology']) == 0 || strcmp('direct-dropship', $posDelivery['methodology']) == 0)) {
                if (!isset($posDelivery['error'])) {
                    return true;
                }
            }

            return false;
        });

        return $non_satellite_deliveries;
    }

    private function getAcceptedQuoteDetailsByIndex($possibleDeliveries, $index) {
        $response = null;

        $count = count($possibleDeliveries);
        if ($count > 0 && $count >= $index) {
            $accepted_quote = $possibleDeliveries[$index - 1];

            $response = $this->getAcceptedQuoteDetails($accepted_quote);
        }

        return $response;
    }

    private function getAcceptedQuoteDetailsByQuoteId($possibleDeliveries, $accepted_quote_id) {
        $response = null;

        if (count($possibleDeliveries) > 0) {
            $accepted_quotes = $this->getQuoteByQuoteId($possibleDeliveries, $accepted_quote_id);

            if (count($accepted_quotes) > 0) {
                $accepted_quote = current($accepted_quotes);

                $response = $this->getAcceptedQuoteDetails($accepted_quote);
            }
        }

        return $response;
    }

    private function getAcceptedQuoteDetails($accepted_quote) {
        $transit_time = 0;
        $estimated_info = null;
        $dropOffLocation = null;
        $methodology = null;

        $shippingCost = 0;
        $accessorialCost = 0;
        $taxesCost = 0;
        $totalCost = 0;

        if (isset($accepted_quote['methodology'])) {
            $methodology = $accepted_quote['methodology'];
        }

        $rate_quoted = $this->getQuoteRate($accepted_quote);

        if (isset($accepted_quote['estimatedLeadTimeDays'])) {
            $transit_time = $accepted_quote['estimatedLeadTimeDays'];

            $estimated_info = $this->getEstimatedDays($methodology, $transit_time);
        } else if (isset($accepted_quote['leadTime'])) {
            $transit_time = $accepted_quote['leadTime'];

            $estimated_info = $this->getEstimatedDays($methodology, $transit_time, true);
        }

        $quote_id = 0;
        if (isset($accepted_quote['deliveryId'])) {
            $quote_id = $accepted_quote['deliveryId'];
        }

        if (isset($accepted_quote['shipments'])) {
            $shipments = $accepted_quote['shipments'];
            if (count($shipments) > 0) {
                $legs = $shipments[0]['legs'];
                if (count($legs) > 0) {
                    if (empty($quote_id) && isset($legs[0]['quoteId'])) {
                        $quote_id = $legs[0]['quoteId'];
                    }

                    if (strcmp('satellite-ltl', $methodology) == 0 || strcmp('network-ltl', $methodology) == 0) {
                        if (array_key_exists('dropOffLocation', $legs[0])) {
                            $dropOffLocation = $legs[0]['dropOffLocation'];
                        } else if (array_key_exists('dropoffLocation', $legs[0])) {
                            $dropOffLocation = $legs[0]['dropoffLocation'];
                        }

                        if (!empty($dropOffLocation)) {
                            $dropOffLocation['addressLine'] = $dropOffLocation['addressLine1'];
                            if (isset($dropOffLocation['addressLine2']) && !empty($dropOffLocation['addressLine2'])) {
                                $dropOffLocation['addressLine'] .= " " . $dropOffLocation['addressLine2'];
                            }
                        }
                    }

                    $isLiftGate = $this->isLiftgate($legs);
                }
            }
        }

        if (isset($accepted_quote['estimate'])) {
            $estimateCost = $accepted_quote['estimate'];
            if (count($estimateCost) > 0) {
                $shippingCost = $estimateCost['base'] + $estimateCost['markup'];
                $accessorialCost = $estimateCost['accessorial'];
                $totalCost = $estimateCost['total'];
            }
        }

        $response = array("name" => '', "type" => 3, "quote_rate" => $rate_quoted,
            "shipping_cost" => $shippingCost, "accessorial_cost" => $accessorialCost, "total_cost" => $totalCost, "taxes_cost" => $taxesCost,
            "quote_id" => $quote_id, "transit_time" => $estimated_info, "leadTime" => $transit_time, "drop_off_location" => $dropOffLocation,
            "lift_gate" => $isLiftGate);

        return $response;
    }

    private function getLowestSatelliteQuoteDetails(array $satellite_deliveries) {
        $response = null;

        if (count($satellite_deliveries) > 0) {
            // Sort the possible deliveries based on estimatedTotal asc
            $this->sortByTotalQuoteAsc($satellite_deliveries);

            $lowest_satellite_quote = current($satellite_deliveries);

            $response = $this->getSatelliteQuoteDetails($lowest_satellite_quote, trans('messages.lowest_satellite_options'), 0);
        }

        return $response;
    }

    private function getFastestSatelliteQuoteDetails(array $satellite_deliveries) {
        $response = null;

        if (count($satellite_deliveries) > 0) {
            // Sort the possible deliveries based on leadTime asc
            $this->sortByLeadTimeAsc($satellite_deliveries);

            $fastest_satellite_quote = current($satellite_deliveries);

            $response = $this->getSatelliteQuoteDetails($fastest_satellite_quote, trans('messages.fastest_satellite_options'), 5);
        }

        return $response;
    }

    private function getLowestQuoteDetails(array $possibleDeliveries) {
        $response = null;

        if (count($possibleDeliveries) > 0) {
            // Sort the possible deliveries based on estimatedTotal asc
            $this->sortByTotalQuoteAsc($possibleDeliveries);

            $lowest_quote = current($possibleDeliveries);

            $response = $this->getStandardQuoteDetails($lowest_quote, trans('messages.lowest_options'), 1);
        }

        return $response;
    }

    private function getFastestQuoteDetails(array $possibleDeliveries) {
        $response = null;

        if (count($possibleDeliveries) > 0) {
            // Sort the possible deliveries based on leadTime asc
            $this->sortByLeadTimeAsc($possibleDeliveries);

            $fastest_quote = current($possibleDeliveries);

            $response = $this->getStandardQuoteDetails($fastest_quote, trans('messages.fastest_options'), 2);
        }

        return $response;
    }

    private function getSatelliteQuoteDetails($quote, $option_name, $option_type) {
        $response = null;

        $transit_time = 0;
        $dropOffLocation = null;
        $estimated_info = null;
        $methodology = null;

        if (isset($quote['methodology'])) {
            $methodology = $quote['methodology'];
        }

        $rate_quoted = $this->getQuoteRate($quote);

        if (isset($quote['estimatedLeadTimeDays'])) {
            $transit_time = $quote['estimatedLeadTimeDays'];

            $estimated_info = $this->getEstimatedDays($methodology, $transit_time);
        } else if (isset($quote['leadTime'])) {
            $transit_time = $quote['leadTime'];

            $estimated_info = $this->getEstimatedDays($methodology, $transit_time);
        }

        $quote_id = 0;
        if (isset($quote['deliveryId'])) {
            $quote_id = $quote['deliveryId'];
        }

        if (isset($quote['shipments'])) {
            $shipments = $quote['shipments'];
            if (count($shipments) > 0) {
                $legs = $shipments[0]['legs'];
                if (count($legs) > 0) {
                    if (empty($quote_id) && isset($legs[0]['quoteId'])) {
                        $quote_id = $legs[0]['quoteId'];
                    }

                    $isLiftGate = $this->isLiftgate($legs);

                    if (array_key_exists('dropOffLocation', $legs[0])) {
                        $dropOffLocation = $legs[0]['dropOffLocation'];
                    } else if (array_key_exists('dropoffLocation', $legs[0])) {
                        $dropOffLocation = $legs[0]['dropoffLocation'];
                    }

                    if (!empty($dropOffLocation)) {
                        $dropOffLocation['addressLine'] = $dropOffLocation['addressLine1'];
                        if (isset($dropOffLocation['addressLine2']) && !empty($dropOffLocation['addressLine2'])) {
                            $dropOffLocation['addressLine'] .= " " . $dropOffLocation['addressLine2'];
                        }

                        $shippingCost = 0;
                        $accessorialCost = 0;
                        $taxesCost = 0;
                        $totalCost = 0;

                        if (isset($quote['estimate'])) {
                            $estimateCost = $quote['estimate'];
                            if (count($estimateCost) > 0) {
                                $shippingCost = $estimateCost['base'] + $estimateCost['markup'];
                                $accessorialCost = $estimateCost['accessorial'];
                                $totalCost = $estimateCost['total'];
                            }
                        }

                        $response = array("name" => $option_name, "type" => $option_type, "quote_rate" => $rate_quoted,
                            "shipping_cost" => $shippingCost, "accessorial_cost" => $accessorialCost, "total_cost" => $totalCost,
                            "taxes_cost" => $taxesCost, "quote_id" => $quote_id, "transit_time" => $estimated_info, "leadTime" => $transit_time,
                            "drop_off_location" => $dropOffLocation, "lift_gate" => $isLiftGate);
                    }
                }
            }
        }

        return $response;
    }

    private function getStandardQuoteDetails($quote, $option_name, $option_type) {
        $transit_time = 0;
        $estimated_info = null;
        $methodology = null;
        $shippingCost = 0;
        $accessorialCost = 0;
        $taxesCost = 0;
        $totalCost = 0;

        if (isset($quote['methodology'])) {
            $methodology = $quote['methodology'];
        }

        $rate_quoted = $this->getQuoteRate($quote);

        if (isset($quote['estimatedLeadTimeDays'])) {
            $transit_time = $quote['estimatedLeadTimeDays'];

            $estimated_info = $this->getEstimatedDays($methodology, $transit_time);
        } else if (isset($quote['leadTime'])) {
            $transit_time = $quote['leadTime'];

            $estimated_info = $this->getEstimatedDays($methodology, $transit_time);
        }

        $quote_id = 0;
        if (isset($quote['deliveryId'])) {
            $quote_id = $quote['deliveryId'];
        }

        if (isset($quote['shipments'])) {
            $shipments = $quote['shipments'];
            if (count($shipments) > 0) {
                $legs = $shipments[0]['legs'];
                if (count($legs) > 0) {
                    if (empty($quote_id) && isset($legs[0]['quoteId'])) {
                        $quote_id = $legs[0]['quoteId'];
                    }

                    $isLiftGate = $this->isLiftgate($legs);
                }
            }
        }

        if (isset($quote['estimate'])) {
            $estimateCost = $quote['estimate'];
            if (count($estimateCost) > 0) {
                $shippingCost = $estimateCost['base'] + $estimateCost['markup'];
                $accessorialCost = $estimateCost['accessorial'];
                $totalCost = $estimateCost['total'];
            }
        }

        $response = array("name" => $option_name, "type" => $option_type, "quote_rate" => $rate_quoted,
            "shipping_cost" => $shippingCost, "accessorial_cost" => $accessorialCost, "total_cost" => $totalCost, "taxes_cost" => $taxesCost,
            "quote_id" => $quote_id, "transit_time" => $estimated_info, "leadTime" => $transit_time, 'lift_gate' => $isLiftGate);

        return $response;
    }

    private function getQuoteRate($quote) {
        $rate_quoted = 0;
        if (isset($quote['estimatedTotal'])) {
            $rate_quoted = $quote['estimatedTotal'];
        } else if (isset($quote['totalQuote'])) {
            $rate_quoted = $quote['totalQuote'];
        } else if (isset($quote['totalEstimate'])) {
            $rate_quoted = $quote['totalEstimate'];
        }

        return $rate_quoted;
    }

    private function isLiftgate($legs) {
        $isLiftGate = false;

        $lastMileLeg = $this->getLastMileLeg($legs);
        if (!empty($lastMileLeg)) {
            if (isset($lastMileLeg['plan']) && isset($lastMileLeg['plan']['handling']) &&
                    isset($lastMileLeg['plan']['handling']['value'])) {
                $value = $lastMileLeg['plan']['handling']['value'];
                $dataType = gettype($value);
                if ($dataType == 'array' &&
                        in_array('liftgate', $value)) {
                    $isLiftGate = true;
                } else if ($dataType == 'string' && strcmp($value, 'liftgate') == 0) {
                    $isLiftGate = true;
                }
            }
        }

        return $isLiftGate;
    }

    private function compareByLeadTime($posDelivery1, $posDelivery2) {
        if (isset($posDelivery1['estimatedLeadTimeDays']) && isset($posDelivery2['estimatedLeadTimeDays'])) {
            if (empty($posDelivery1['estimatedLeadTimeDays'])) {
                return 1;
            } else if (empty($posDelivery2['estimatedLeadTimeDays'])) {
                return -1;
            } else if ($posDelivery1['estimatedLeadTimeDays'] == $posDelivery2['estimatedLeadTimeDays']) {
                return 0;
            } else if ($posDelivery1['estimatedLeadTimeDays'] > $posDelivery2['estimatedLeadTimeDays']) {
                return 1;
            }
        } else if (isset($posDelivery1['leadTime']) && isset($posDelivery2['leadTime'])) {
            if (empty($posDelivery1['leadTime'])) {
                return 1;
            } else if (empty($posDelivery2['leadTime'])) {
                return -1;
            } else if ($posDelivery1['leadTime'] == $posDelivery2['leadTime']) {
                return 0;
            } else if ($posDelivery1['leadTime'] > $posDelivery2['leadTime']) {
                return 1;
            }
        }

        return -1;
    }

    private function compareByTotalQuote($posDelivery1, $posDelivery2) {
        if (isset($posDelivery1['estimatedTotal']) && isset($posDelivery2['estimatedTotal'])) {
            if (empty($posDelivery1['estimatedTotal'])) {
                return 1;
            } else if (empty($posDelivery2['estimatedTotal'])) {
                return -1;
            } else if ($posDelivery1['estimatedTotal'] == $posDelivery2['estimatedTotal']) {
                return 0;
            } else if ($posDelivery1['estimatedTotal'] > $posDelivery2['estimatedTotal']) {
                return 1;
            }
        } else if (isset($posDelivery1['totalQuote']) && isset($posDelivery2['totalQuote'])) {
            if (empty($posDelivery1['totalQuote'])) {
                return 1;
            } else if (empty($posDelivery2['totalQuote'])) {
                return -1;
            } else if ($posDelivery1['totalQuote'] == $posDelivery2['totalQuote']) {
                return 0;
            } elseif ($posDelivery1['totalQuote'] > $posDelivery2['totalQuote']) {
                return 1;
            }
        }

        return -1;
    }

    private function sortByLeadTimeAsc(&$possibleDeliveries) {
        // Sort the possible deliveries based on leadTime asc
        usort($possibleDeliveries, function ($posDelivery1, $posDelivery2) {
            if (isset($posDelivery1['estimatedLeadTimeDays']) && isset($posDelivery2['estimatedLeadTimeDays'])) {
                if (empty($posDelivery1['estimatedLeadTimeDays'])) {
                    return 1;
                } else if (empty($posDelivery2['estimatedLeadTimeDays'])) {
                    return -1;
                } else if ($posDelivery1['estimatedLeadTimeDays'] == $posDelivery2['estimatedLeadTimeDays']) {
                    return $this->compareByTotalQuote($posDelivery1, $posDelivery2);
                } else if ($posDelivery1['estimatedLeadTimeDays'] > $posDelivery2['estimatedLeadTimeDays']) {
                    return 1;
                }
            } else if (isset($posDelivery1['leadTime']) && isset($posDelivery2['leadTime'])) {
                if (empty($posDelivery1['leadTime'])) {
                    return 1;
                } else if (empty($posDelivery2['leadTime'])) {
                    return -1;
                } else if ($posDelivery1['leadTime'] == $posDelivery2['leadTime']) {
                    return $this->compareByTotalQuote($posDelivery1, $posDelivery2);
                } else if ($posDelivery1['leadTime'] > $posDelivery2['leadTime']) {
                    return 1;
                }
            }

            return -1;
        });
    }

    private function sortByTotalQuoteAsc(&$possibleDeliveries) {
        // Sort the possible deliveries based on leadTime asc
        usort($possibleDeliveries, function ($posDelivery1, $posDelivery2) {
            if (isset($posDelivery1['estimatedTotal']) && isset($posDelivery2['estimatedTotal'])) {
                if (empty($posDelivery1['estimatedTotal'])) {
                    return 1;
                } else if (empty($posDelivery2['estimatedTotal'])) {
                    return -1;
                } else if ($posDelivery1['estimatedTotal'] == $posDelivery2['estimatedTotal']) {
                    return $this->compareByLeadTime($posDelivery1, $posDelivery2);
                } else if ($posDelivery1['estimatedTotal'] > $posDelivery2['estimatedTotal']) {
                    return 1;
                }
            } else if (isset($posDelivery1['totalQuote']) && isset($posDelivery2['totalQuote'])) {
                if (empty($posDelivery1['totalQuote'])) {
                    return 1;
                } else if (empty($posDelivery2['totalQuote'])) {
                    return -1;
                } else if ($posDelivery1['totalQuote'] == $posDelivery2['totalQuote']) {
                    return $this->compareByLeadTime($posDelivery1, $posDelivery2);
                } else if ($posDelivery1['totalQuote'] > $posDelivery2['totalQuote']) {
                    return 1;
                }
            }

            return -1;
        });
    }

    private function getEstimatedDays($methodology, $transit_time, $ignoreLabel = false) {
        $toAdd = 0;

        if (strcmp('direct-ltl', $methodology) == 0) {
            $toAdd = getenv('DIRECT_LTL_PADDING');
        } else if (strcmp('network-ltl', $methodology) == 0) {
            $toAdd = getenv('NETWORK_LTL_PADDING');
        } else if (strcmp('satellite-ltl', $methodology) == 0) {
            $toAdd = getenv('SATELLITE_LTL_PADDING');
        } else if (strcmp('direct-dropship', $methodology) == 0) {
            $toAdd = getenv('DIRECT_DROPSHIP_PADDING');
        }

        $start = $transit_time;
        $end = $transit_time + $toAdd;

        $estimated_info = '';
        if ($ignoreLabel) {
            $estimated_info = trans('messages.transit_time', ['start' => $start, 'end' => $end]);
        } else if ($start === $end) {
            $estimated_info = trans('messages.estimated_same_day_transit_time', ['start' => $start]);
        } else {
            $estimated_info = trans('messages.estimated_transit_time', ['start' => $start, 'end' => $end]);
        }

        return $estimated_info;
    }

    private function updateExpiredQuotes(array $estimate_ids, $status) {
        DB::table('gw_estimates')
                ->whereIn('estimate_id', $estimate_ids)
                ->update(array('status' => $status));
    }

    /**
     * Get the single quote
     * 
     * @param Request $request
     * @return type
     */
    public function quote(Request $request) {
        $user_id = $_REQUEST['AUTHUSERDATA']['gw_user_id'];
        $company_id = $_REQUEST['AUTHUSERDATA']['gw_company_id'];

        $estimate_id = $request->header('estimateid');

        if (!isset($estimate_id) || empty($estimate_id)) {
            $errorMessage = trans('messages.missing_single_field', ['name' => 'estimateid']);
            return $this->logErrorAndSendResponse($errorMessage);
        }

        try {
            // Call the DB on that user id
            DB::setFetchMode(PDO::FETCH_ASSOC);

            $query = '';
            if (UserService::isSuperUser()) {
                $query = 'select user_id, estimate_id, status, accepted_quote_id, accepted_user_id, accepted_delivery_type, expiry_date, requested_date, effective_date, quotes_data from gw_estimates '
                        . 'where company_id = ' . $company_id . " and estimate_id = '" . $estimate_id . "'";
            } else {
                $query = 'select user_id, estimate_id, status, accepted_quote_id, accepted_user_id, accepted_delivery_type, expiry_date, requested_date, effective_date, quotes_data from gw_estimates '
                        . 'where user_id = ' . $user_id . " and estimate_id = '" . $estimate_id . "'";
            }

            $results = DB::select($query);

            DB::setFetchMode(PDO::FETCH_CLASS);

            if (count($results) > 0) {
                $userMap = array();
                $quote_data = $this->getQuoteData($results[0], $userMap);

                if ($quote_data['run_expiry'] == true) {
                    unset($quote_data['run_expiry']);
                    $this->updateExpiredQuotes(array($quote_data['estimate_id']), 'expired');
                }

                return $this->sendSuccessResponse($quote_data);
            } else {
                $errorMessage = trans('messages.rfq_not_found');
                return $this->logErrorAndSendResponse($errorMessage);
            }
        } catch (\Exception $exception) {
            $errorMessage = trans('messages.rfq_stats_failed');
            return $this->logExceptionAndSendResponse($exception, $errorMessage);
        }
    }

    /**
     * Insert a new quote
     * 
     * @param Request $request
     * @return type
     */
    public function requestquote(Request $request) {
        $ignoreValueDeliveryFlag = false;
        $ignoreValueDelivery = $request->header("ignoreValueDelivery");
        if (empty($ignoreValueDelivery) || $ignoreValueDelivery === null || $ignoreValueDelivery === '') {
            $ignoreValueDeliveryFlag = false;
        } else if (Utils::validateBooleanField($ignoreValueDelivery)) {
            $ignoreValueDeliveryFlag = true;
        }

        $data = $request->json()->all();

        if (!isset($data['request']) && (empty($data['request']))) {
            $errorMessage = trans('messages.rfq_request_data_miss');
            return $this->logErrorAndSendResponse($errorMessage);
        }

        $input_data = $data['request'];

        if (isset($input_data)) {
            $rules = array(
                'originLocation' => 'required',
                'originLocation.postalCode' => 'required',
                'originLocation.country' => 'required',
                'originLocation.location_type' => 'required',
                'destinationLocation' => 'required',
                'destinationLocation.postalCode' => 'required',
                'destinationLocation.country' => 'required',
                'destinationLocation.location_type' => 'required',
                'freightItems' => 'required',
            );

            $validator = Validator::make($input_data, $rules);

            if ($validator->fails()) {
                $failureMsg = trans('messages.mandatory_head');

                $temp = $validator->errors()->all();
                $errorMessage = $failureMsg . implode(", ", $temp);

                return $this->logErrorAndSendResponse($errorMessage);
            } else {
                $input_freight_data = $input_data['freightItems'];

                $freight_rules = array(
                    'freightItemId' => 'required',
                    'classCode' => 'required',
                    'quantity' => 'required',
                    'unit' => 'required',
                    'description' => 'required',
                    'dimensions' => 'required',
                    'dimensions.height' => 'required',
                    'dimensions.width' => 'required',
                    'dimensions.length' => 'required',
                    'dimensions.unit' => 'required',
                    'weight' => 'required',
                    'weight.unit' => 'required',
                    'weight.weight' => 'required',
                );

                $temp1 = array();
                foreach ($input_freight_data as $freightitems) {
                    $validator1 = Validator::make($freightitems, $freight_rules);

                    if ($validator1->fails()) {
                        $temp1[] = $validator1->errors()->all();
                    }
                }

                $i = 1;
                $errorMessage = array();
                foreach ($temp1 as &$freighitemserror) {
                    $errorMessage[$i] = implode(", ", $freighitemserror);
                    $i++;
                }

                $freightErrMessage = NULL;
                if (isset($errorMesssage)) {
                    for ($j = 1; $j <= count($errorMessage); $j++) {
                        $freightErrMessage .= "Frieght Index of $j: " . $errorMessage[$j];
                    }

                    return $this->logErrorAndSendResponse($freightErrMessage);
                } else {
                    $orgState = '';
                    if (array_key_exists('state', $data['request']['originLocation'])) {
                        $orgState = $data['request']['originLocation']['state'];
                    }

                    $destState = '';
                    if (array_key_exists('state', $data['request']['destinationLocation'])) {
                        $destState = $data['request']['destinationLocation']['state'];
                    }

                    try {
                        $originLocationArray = $data['request']['originLocation'];
                        $destinationLocationArray = $data['request']['destinationLocation'];

                        $originLocationType = $originLocationArray['location_type'];
                        unset($data['request']['originLocation']['location_type']);

                        $data['request']['originLocation']['information'] = $this->getLocationWithDockValue($originLocationType);

                        $originLocationResponse = $this->checkQuoteAddress($originLocationArray);
                        $originLocationStatusCode = $originLocationResponse['statusCode'];

                        if ($originLocationStatusCode == 200) {
                            $data['request']['originLocation']['stateFullName'] = $data['request']['originLocation']['state'];
                            $data['request']['originLocation']['state'] = $originLocationResponse['data']['state'];
                            $data['request']['originLocation']['postalCode'] = strtoupper($data['request']['originLocation']['postalCode']);

                            if (!isset($data['request']['originLocation']['contact'])) {
                                $data['request']['originLocation']['contact'] = array("name" => "");
                            } else {
                                if (!isset($data['request']['originLocation']['contact']['name'])) {
                                    $data['request']['originLocation']['contact']['name'] = "";
                                }
                            }
                        } else {
                            http_response_code($originLocationStatusCode);

                            return response()->json([
                                        'statusCode' => $originLocationStatusCode,
                                        'success' => false,
                                        'message' => 'Origin location: ' . $originLocationResponse['message'],
                                        'devMsg' => 'Origin location: ' . $originLocationResponse['devMsg'],
                                            ], $originLocationStatusCode);
                        }

                        $destinationLocationType = $destinationLocationArray['location_type'];
                        unset($data['request']['destinationLocation']['location_type']);

                        $data['request']['destinationLocation']['information'] = $this->getLocationWithDockValue($destinationLocationType);

                        $destinationLocationResponse = $this->checkQuoteAddress($destinationLocationArray);
                        $destinationLocationStatusCode = $destinationLocationResponse['statusCode'];

                        if ($destinationLocationStatusCode == 200) {
                            $data['request']['destinationLocation']['stateFullName'] = $data['request']['destinationLocation']['state'];
                            $data['request']['destinationLocation']['state'] = $destinationLocationResponse['data']['state'];
                            $data['request']['destinationLocation']['postalCode'] = strtoupper($data['request']['destinationLocation']['postalCode']);

                            if (!isset($data['request']['destinationLocation']['contact'])) {
                                $data['request']['destinationLocation']['contact'] = array("name" => "");
                            } else {
                                if (!isset($data['request']['originLocation']['contact']['name'])) {
                                    $data['request']['originLocation']['contact']['name'] = "";
                                }
                            }
                        } else {
                            http_response_code($destinationLocationStatusCode);

                            return response()->json([
                                        'statusCode' => $destinationLocationStatusCode,
                                        'success' => false,
                                        'message' => 'Destination location: ' . $destinationLocationResponse['message'],
                                        'devMsg' => 'Destination location: ' . $destinationLocationResponse['devMsg'],
                                            ], $destinationLocationStatusCode);
                        }

                        $freightItemArray = $data['request']['freightItems'];
                        for ($i = 0; $i <= count($freightItemArray) - 1; $i++) {
                            $classCode = number_format((float) $freightItemArray[$i]['classCode'], 2, '.', '');
                            $freightItemArray[$i]['classCode'] = $classCode;

                            if (!empty(getenv("DIMENSION_UNIT"))) {
                                $freightItemArray[$i]['dimensions']['unit'] = getenv("DIMENSION_UNIT");
                            } else {
                                $freightItemArray[$i]['dimensions']['unit'] = 'inches';
                            }

                            if (!empty(getenv("WEIGHT_UNIT"))) {
                                $freightItemArray[$i]['weight']['unit'] = getenv("WEIGHT_UNIT");
                            } else {
                                $freightItemArray[$i]['weight']['unit'] = 'pounds';
                            }

                            if (!isset($freightItemArray[$i]['description'])) {
                                $freightItemArray[$i]['description'] = '';
                            }
                        }

                        $data['request']['freightItems'] = $freightItemArray;

                        if (strcmp($originLocationType, 'limited-access') == 0 || strcmp($destinationLocationType, 'limited-access') == 0) {
                            $errorMessage = trans('messages.rfq_limited_access');

                            $this->handleManualQuote($data, $orgState, $destState, $errorMessage);

                            $quote_data = array();
                            $quote_data['quotes_available'] = false;

                            return $this->sendSuccessResponse($quote_data, trans('messages.rfq_added'));
                        } else {
                            $estimate_resp = $this->createEstimates($data['request']);

                            if (isset($estimate_resp->_id) && !is_null($estimate_resp->_id)) {
                                $status = "pending";
                                $quotes_available = true;

                                if (!($this->hasValidQuote($estimate_resp->possibleDeliveries))) {
                                    $failiure_reason = trans('messages.quote_not_available');
                                    $this->prepareAndSendFailedQuoteEmail($data, $orgState, $destState, $failiure_reason, $estimate_resp->_id);
                                    $quotes_available = false;
                                    $status = "manual";
                                }

                                DB::beginTransaction();

                                $estimate_data = $this->insertEstimate($estimate_resp->_id, $estimate_resp, $status);

                                DB::commit();

                                $userMap = array();

                                if ($quotes_available) {
                                    $quote_data = $this->getQuoteData($estimate_data, $userMap, $ignoreValueDeliveryFlag);
                                    $quote_data['quotes_available'] = $quotes_available;

                                    return $this->sendSuccessResponse($quote_data, trans('messages.rfq_added'));
                                } else {
                                    $errorMesssage = trans('messages.quote_not_available');

                                    return $this->logErrorAndSendResponse($errorMesssage, $errorMesssage, true, 500);
                                }
                            } else {
                                $errorMessage = trans('messages.rfq_failed');
                                $devErrorMsg = $errorMesssage;

                                if (isset($estimate_resp->message) && !empty($estimate_resp->message)) {
                                    $devErrorMsg = $estimate_resp->message;
                                    app('sentry')->captureMessage($devErrorMsg, NULL, 'error');
                                }

                                return $this->logErrorAndSendResponse($errorMesssage, $devErrorMsg, true, 500);
                            }
                        }
                    } catch (ApiException $apiException) {
                        DB::rollBack();

                        $apiReponseObject = $apiException->getResponseBody();
                        $devMsg = json_encode($apiReponseObject);
                        $errorObject = json_decode($devMsg, true);

                        $errorMessage = trans('messages.rfq_failed');
                        if (isset($errorObject['error'])) {
                            if (isset($errorObject['error']['details'])) {
                                if (isset($errorObject['error']['details']['reason'])) {

                                    $estimatedErrData = $errorObject['error']['details']['reason'];
                                    if (isset($estimatedErrData['errors']) && count($estimatedErrData['errors']) > 0) {
                                        foreach ($estimatedErrData['errors'] as $value) {
                                            $errorMessage = $value;
                                            break;
                                        }
                                    }
                                }
                            }
                        }

                        $this->handleManualQuote($data, $orgState, $destState, $errorMessage, $apiReponseObject);

                        return $this->logExceptionAndSendResponse($apiException, $errorMessage, $devMsg);
                    } catch (\Exception $exception) {
                        DB::rollBack();

                        $errorMessage = trans('messages.rfq_failed');

                        $error_data = ['error_message' => $exception->getMessage()];
                        $this->handleManualQuote($data, $orgState, $destState, $errorMessage, $error_data);

                        return $this->logExceptionAndSendResponse($exception, $errorMessage);
                    }
                }
            }
        }
    }

    private function getLocationWithDockValue($locationType) {
        $locationInfo = null;

        if (strcmp($locationType, "commercial-nodock") === 0) {
            $locationInfo = array("locationType" => array("value" => "commercial"),
                "dock" => array("value" => "no-dock"));
        } else if (strcmp($locationType, "commercial-hasdock") === 0) {
            $locationInfo = array("locationType" => array("value" => "commercial"),
                "dock" => array("value" => "has-dock"));
        } else {
            $locationInfo = array("locationType" => array("value" => $locationType));
        }

        return $locationInfo;
    }

    private function hasValidQuote($possibleDeliveries) {
        $valid_deliveries = null;

        if (!empty($possibleDeliveries)) {
            $valid_deliveries = array_filter($possibleDeliveries, function ($posDelivery) {
                if (!isset($posDelivery->error)) {
                    return true;
                }

                return false;
            });
        }

        return !empty($valid_deliveries) && count($valid_deliveries) > 0;
    }

    private function handleManualQuote($estimateRequest, $orgState, $destState, $errorMessage, $error_data = NULL) {
        $manual_estimate_data = $estimateRequest['request'];
        $manual_estimate_data['possibleDeliveries'] = array();

        DB::beginTransaction();

        $estimate_data = $this->insertEstimate(null, $manual_estimate_data, "manual", $error_data);

        $this->prepareAndSendFailedQuoteEmail($estimateRequest, $orgState, $destState, $errorMessage, $estimate_data['estimate_id']);

        DB::commit();
    }

    private function insertEstimate($estimate_id, $estimate_response, $status, $error_data = NULL) {
        $user_id = $_REQUEST['AUTHUSERDATA']['gw_user_id'];
        $company_id = $_REQUEST['AUTHUSERDATA']['gw_company_id'];

        if (empty($estimate_id)) {
            $estimate_id = uniqid("", true);
        }

        $currentTimestamp = date("Y-m-d H:i:s", time());
        $current_date = date('Y-m-d');
        $estimate_data = [
            'estimate_id' => $estimate_id,
            'user_id' => $user_id,
            'company_id' => $company_id,
            'status' => $status,
            'requested_date' => $current_date,
            'quotes_data' => json_encode($estimate_response),
            'order_id' => 1,
            'created_time' => $currentTimestamp,
            'updated_time' => $currentTimestamp,
        ];

        if (strcmp($status, 'manual') != 0) {
            $week_in_seconds = 24 * 3600 * 7;
            $expiry_date = date('Y-m-d', strtotime($current_date) + $week_in_seconds);
            $estimate_data['expiry_date'] = $expiry_date;

            $estimate_data['effective_date'] = $current_date;
        }

        if (!empty($error_data)) {
            $estimate_data['error_data'] = json_encode($error_data);
        }

        DB::table('gw_estimates')->insertGetId($estimate_data);

        return $estimate_data;
    }

    public function acceptQuote(Request $request) {
        $ignoreValueDeliveryFlag = false;
        $ignoreValueDelivery = $request->header("ignoreValueDelivery");
        if (empty($ignoreValueDelivery) || $ignoreValueDelivery === null || $ignoreValueDelivery === '') {
            $ignoreValueDeliveryFlag = false;
        } else if (Utils::validateBooleanField($ignoreValueDelivery)) {
            $ignoreValueDeliveryFlag = true;
        }

        $requestData = $request->json()->all();
        if (!$requestData) {
            $errorMessage = trans('messages.payload_missing');
            return $this->logErrorAndSendResponse($errorMessage);
        }

        $estimate_id = $requestData['estimate_id'];
        $selected_index = $requestData['selected_index'];
        $selected_option_name = $requestData['selected_option_name'];
        $rate_quoted = $requestData['rate_quoted'];

        $user_id = $_REQUEST['AUTHUSERDATA']['gw_user_id'];
        $company_id = $_REQUEST['AUTHUSERDATA']['gw_company_id'];

        DB::setFetchMode(PDO::FETCH_ASSOC);

        $query = '';
        if (UserService::isSuperUser()) {
            $query = 'select user_id, estimate_id, status, accepted_quote_id, accepted_user_id, accepted_delivery_type, expiry_date, requested_date, effective_date, quotes_data from gw_estimates '
                    . 'where company_id = ' . $company_id . " and estimate_id = '" . $estimate_id . "'";
        } else {
            $query = 'select user_id, estimate_id, status, accepted_quote_id, accepted_user_id, accepted_delivery_type, expiry_date, requested_date, effective_date, quotes_data from gw_estimates '
                    . 'where user_id = ' . $user_id . " and estimate_id = '" . $estimate_id . "'";
        }

        $results = DB::select($query);

        DB::setFetchMode(PDO::FETCH_CLASS);

        $estimate_data = null;
        if (count($results) > 0) {
            $estimate_data = $results[0];

            if (strcmp($estimate_data['status'], 'expired') == 0) {
                return $this->logErrorAndSendResponse(trans('messages.requested_quote_expired'));
            } else if (strcmp($estimate_data['status'], 'accepted') == 0) {
                return $this->logErrorAndSendResponse(trans('messages.quote_already_accepted'));
            } else if (strcmp($estimate_data['status'], 'canceled') == 0) {
                return $this->logErrorAndSendResponse(trans('messages.requested_quote_cancelled'));
            }

            if (strtotime($estimate_data['expiry_date']) <= time()) {
                return $this->logErrorAndSendResponse(trans('messages.requested_quote_expired'));
            }
        } else {
            $errorMessage = trans('messages.quote_not_found');
            return $this->logErrorAndSendResponse($errorMessage);
        }

        $quotesData = json_decode($estimate_data['quotes_data'], true);

        $originLocation = $requestData['originLocation'];
        if (!isset($originLocation['addressLine1'])) {
            $originLocation['addressLine1'] = '';
        }
        if (!isset($originLocation['contact']['name'])) {
            $originLocation['contact']['name'] = '';
        }
        if (!isset($originLocation['contact']['phone'])) {
            $originLocation['contact']['phone'] = '';
        }
        if (!isset($originLocation['contact']['email'])) {
            $originLocation['contact']['email'] = '';
        }
        if (!isset($originLocation['contact']['company'])) {
            $originLocation['contact']['company'] = '';
        }

        $originLocation['state'] = $quotesData['originLocation']['state'];
        $originLocation['city'] = $quotesData['originLocation']['city'];
        $originLocation['country'] = $quotesData['originLocation']['country'];
        $originLocation['postalCode'] = $quotesData['originLocation']['postalCode'];
        $originLocation['information'] = $quotesData['originLocation']['information'];

        $destinationLocation = $requestData['destinationLocation'];
        if (!isset($destinationLocation['addressLine1'])) {
            $destinationLocation['addressLine1'] = '';
        }
        if (!isset($destinationLocation['contact']['name'])) {
            $destinationLocation['contact']['name'] = '';
        }
        if (!isset($destinationLocation['contact']['phone'])) {
            $destinationLocation['contact']['phone'] = '';
        }
        if (!isset($destinationLocation['contact']['email'])) {
            $destinationLocation['contact']['email'] = '';
        }
        if (!isset($destinationLocation['contact']['company'])) {
            $destinationLocation['contact']['company'] = '';
        }

        $destinationLocation['state'] = $quotesData['destinationLocation']['state'];
        $destinationLocation['city'] = $quotesData['destinationLocation']['city'];
        $destinationLocation['country'] = $quotesData['destinationLocation']['country'];
        $destinationLocation['postalCode'] = $quotesData['destinationLocation']['postalCode'];
        $destinationLocation['information'] = $quotesData['destinationLocation']['information'];

        $quote_details = $this->getQuoteDetailsByIndex($estimate_data, $selected_index, $ignoreValueDeliveryFlag);

        $accepted_quote_id = 0;
        if (isset($quote_details['deliveryId'])) {
            $accepted_quote_id = $quote_details['deliveryId'];
        }

        if (empty($accepted_quote_id) && isset($quote_details['shipments'])) {
            $shipments = $quote_details['shipments'];
            if (count($shipments) > 0) {
                $legs = $shipments[0]['legs'];
                if (count($legs) > 0) {
                    if (isset($legs[0]['quoteId'])) {
                        $accepted_quote_id = $legs[0]['quoteId'];
                    }
                }
            }
        }

        $data = array(
            "estimate_id" => $estimate_id,
            "accepted_quote_id" => $accepted_quote_id,
            "accepted_quote_type" => $selected_option_name,
            'request' => array('originLocation' => $originLocation,
                'destinationLocation' => $destinationLocation,
                'rateQuoted' => $rate_quoted,
                'referenceNumber' => $requestData['referenceNumber']
            )
        );

        return $this->doDelivery($data, $quote_details);
    }

    /**
     * Accepts a quote
     * 
     * @param Request $request
     * @return type
     */
    public function confirmorder(Request $request) {
        $data = $request->json()->all();
        if (!$data) {
            $errorMessage = trans('messages.payload_missing');
            return $this->logErrorAndSendResponse($errorMessage);
        }

        if (!isset($data['request']) && (empty($data['request']))) {
            $errorMessage = trans('messages.request_missing');
            return $this->logErrorAndSendResponse($errorMessage);
        }

        $user_id = $_REQUEST['AUTHUSERDATA']['gw_user_id'];
        $company_id = $_REQUEST['AUTHUSERDATA']['gw_company_id'];

        $estimate_id = $data['estimate_id'];

        DB::setFetchMode(PDO::FETCH_ASSOC);

        $query = '';
        if (UserService::isSuperUser()) {
            $query = 'select estimate_id, status, expiry_date, quotes_data from gw_estimates where company_id = '
                    . $company_id . " and estimate_id = '" . $estimate_id . "'";
        } else {
            $query = 'select estimate_id, status, expiry_date, quotes_data from gw_estimates where user_id = '
                    . $user_id . " and estimate_id = '" . $estimate_id . "'";
        }

        $results = DB::select($query);

        DB::setFetchMode(PDO::FETCH_CLASS);

        $estimate_data = null;
        if (count($results) > 0) {
            $estimate_data = $results[0];

            if (strcmp($estimate_data['status'], 'expired') == 0) {
                return $this->logErrorAndSendResponse(trans('messages.requested_quote_expired'));
            } else if (strcmp($estimate_data['status'], 'accepted') == 0) {
                return $this->logErrorAndSendResponse(trans('messages.quote_already_accepted'));
            } else if (strcmp($estimate_data['status'], 'canceled') == 0) {
                return $this->logErrorAndSendResponse(trans('messages.requested_quote_cancelled'));
            }

            if (strtotime($estimate_data['expiry_date']) <= time()) {
                return $this->logErrorAndSendResponse(trans('messages.requested_quote_expired'));
            }
        } else {
            $errorMessage = trans('messages.quote_not_found');
            return $this->logErrorAndSendResponse($errorMessage);
        }

        // Prepare data to inset into gw_deliveries
        $accepted_quote_id = $data['accepted_quote_id'];
        $quote_details = $this->getQuoteDetailsByQuoteId($estimate_data, $accepted_quote_id);

        return $this->doDelivery($data, $quote_details);
    }

    /**
     * Accepts a quote
     * 
     * @param Request $request
     * @return type
     */
    private function doDelivery($data, $quote_details) {
        $user_id = $_REQUEST['AUTHUSERDATA']['gw_user_id'];
        $estimate_id = $data['estimate_id'];
        $accepted_quote_type = $data['accepted_quote_type'];
        $accepted_quote_id = $data['accepted_quote_id'];

        try {
            $delivery_id = null;
            $deliveryData = null;
            $errorData = null;
            $tenderingSuccess = true;
            try {
                $deliveryInp = array();
                $tomorrow_in_timestamp = time() + (24 * 3600);
                $deliveryInp['desiredStartDate'] = date("Y-m-d\TH:i:s.v\Z", $tomorrow_in_timestamp);

                $leadTime = 0;
                if (isset($quote_details['estimatedLeadTimeDays'])) {
                    $leadTime = $quote_details['estimatedLeadTimeDays'];
                } else if (isset($quote_details['leadTime'])) {
                    $leadTime = $quote_details['leadTime'];
                }
                //$deliveryInp['desiredCompletionDate'] = date("Y-m-d\TH:i:s.v\Z", $tomorrow_in_timestamp + ($leadTime * 24 * 3600));
                $deliveryInp['desiredCompletionDate'] = NULL;

                if (!empty(getenv("MAIL_ACCEPT_QUOTE_NOTIFICATION"))) {
                    $deliveryInp['notificationEmails'] = array(getenv("MAIL_ACCEPT_QUOTE_NOTIFICATION"));
                }

                $shipments = $quote_details['shipments'];
                if (count($shipments) > 0) {
                    foreach ($shipments as $shipment) {
                        if (!isset($shipment['shipmentId'])) {
                            $shipment['shipmentId'] = '';
                        }
                    }
                }

                $quote_details['originLocation'] = $data['request']['originLocation'];
                $quote_details['destinationLocation'] = $data['request']['destinationLocation'];

                $quote_details['shipments'][0]['legs'][0]['originLocation']['contact'] = $data['request']['originLocation']['contact'];
                $quote_details['shipments'][0]['legs'][0]['destinationLocation']['contact'] = $data['request']['destinationLocation']['contact'];

                $quote_details['shipments'][0]['legs'][0]['originLocation']['addressLine1'] = $data['request']['originLocation']['addressLine1'];
                $quote_details['shipments'][0]['legs'][0]['destinationLocation']['addressLine1'] = $data['request']['destinationLocation']['addressLine1'];

                $deliveryInp['delivery'] = $quote_details;

                if (isset($data['request']['referenceNumber'])) {
                    $deliveryInp['delivery']['referenceIds'] = array("referenceNumber" => $data['request']['referenceNumber']);
                }

                $deliveryData = $this->createDeliveries($deliveryInp);

                if (isset($deliveryData->_id) && !is_null($deliveryData->_id)) {
                    $delivery_id = $deliveryData->_id;
                }
            } catch (ApiException $apiException) {
                $tenderingSuccess = false;
                $errorMessage = trans('messages.delivery_order_error_api');
                $errorData = $apiException->getResponseBody();
                $devMsg = json_encode($errorData);

                $this->logErrorAndSendToSentry($apiException, $errorMessage, $devMsg);
            } catch (Exception $ex) {
                $tenderingSuccess = false;
                $errorMessage = trans('messages.delivery_order_error');

                $errorData = ['error_message' => $ex->getMessage()];

                $this->logErrorAndSendToSentry($ex, $errorMessage);
            }

            DB::beginTransaction();
            $this->insertDelivery($delivery_id, json_encode($deliveryData), $estimate_id, $accepted_quote_id, $accepted_quote_type, $errorData, $quote_details);

            DB::table('gw_estimates')
                    ->where('estimate_id', $estimate_id)
                    ->update([
                        'status' => "accepted", 'accepted_quote_id' => $accepted_quote_id,
                        'accepted_user_id' => $user_id,
                        'accepted_delivery_type' => $accepted_quote_type
            ]);

            // Mail to the supplier 
            $templateData = $this->prepareEmailData($data, $user_id, $estimate_id, $accepted_quote_id, $accepted_quote_type);
            $template = 'gateway_supplier_email';

            //$subject = 'Your Delivery Order confirmed with Gateway Supply Chain. Quote ID: ' . $estimate_id;
            $subject = trans('messages.delivery_order_confirm_email_subject') . $estimate_id;

            $logged_in_email = $_REQUEST['AUTHUSERDATA']['email'];
            $from = getenv("MAIL_FROM_ADDRESS");

            $this->sendEmail($subject, $template, $templateData, $from, $logged_in_email);

            // Mail to the SC Team
            $userCompanyInfo = UserService::getUserAsArrayWithCompanyInfo($user_id);
            $templateSC = 'gateway_supplier_chain_email';
            if ($tenderingSuccess) {
                $subjectSC = 'Quote Acceptance from ' . $userCompanyInfo['first_name'] .
                        ' ' . $userCompanyInfo['last_name'] . ' at ' . $userCompanyInfo['name'] . ' (Quote ID: ' . $estimate_id . ')';
                $templateData['status'] = true;
            } else {
                $subjectSC = 'Action required: Failed to write this tender request from ' . $userCompanyInfo['name'] . ' to Smartsheet.';
                $templateData['status'] = false;
            }

            $templateData['shipper_full_name'] = $userCompanyInfo['first_name'] . ' ' . $userCompanyInfo['last_name'];
            $templateData['shipper_email'] = $userCompanyInfo['email'];
            $templateData['shipper_company'] = $userCompanyInfo['legal_name'];
            $templateData['requester_phone'] = $userCompanyInfo['phone_number'];

            $to = NULL;
            $cc = NULL;
            $bcc = NULL;
            if (empty(getenv("MAIL_TO_ACCEPT_QUOTE"))) {
                $to = $logged_in_email;
            } else {
                $to = explode(',', getenv("MAIL_TO_ACCEPT_QUOTE"));
            }

            if (!empty(getenv("MAIL_CC"))) {
                $cc = explode(',', getenv("MAIL_CC"));
            }

            if (!empty(getenv("MAIL_BCC"))) {
                $bcc = explode(',', getenv("MAIL_BCC"));
            }

            if (isset($quote_details['shipments']) && count($quote_details['shipments'] > 0)) {
                if (isset($quote_details['shipments'][0]['legs'])) {
                    $lastMileLeg = $this->getLastMileLeg($quote_details['shipments'][0]['legs']);
                    if (!empty($lastMileLeg)) {
                        $templateData['carrier_name'] = $lastMileLeg['carrierName'];
                    }
                }
            }

            $data['quote_data'] = $quote_details;

            $jsonAttachmentData = json_encode($data);
            $this->sendEmail($subjectSC, $templateSC, $templateData, $from, $to, $cc, $bcc, "accepted_quote_details.json", $jsonAttachmentData);

            DB::commit();

            $response = array("tracking_url" => getenv("APP_URL") . "/tracking/" . $delivery_id);
            return $this->sendSuccessResponse($response, trans('messages.shipping_order_confirm'));
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->logExceptionAndSendResponse($exception, trans('messages.shipping_order_error'));
        }
    }

    private function getLastMileLeg($legs) {
        $lastMileLeg = null;

        if (!empty($legs) && count($legs) > 0) {
            foreach ($legs as $leg) {
                if (isset($leg['mechanism']) && strcmp($leg['mechanism'], "last-mile") == 0) {
                    $lastMileLeg = $leg;
                    break;
                }
            }
        }

        return $lastMileLeg;
    }

    private function insertDelivery($delivery_id, $delivery_response, $estimate_id, $accepted_quote_id, $accepted_quote_type, $error_data = NULL, $delivery_input_data = NULL) {
        $user_id = $_REQUEST['AUTHUSERDATA']['gw_user_id'];
        $company_id = $_REQUEST['AUTHUSERDATA']['gw_company_id'];

        if (empty($delivery_id)) {
            $delivery_id = uniqid("", true);
        }

        $currentTimestamp = date("Y-m-d H:i:s", time());
        $delivery_data = [
            'delivery_id' => $delivery_id,
            'user_id' => $user_id,
            'company_id' => $company_id,
            'estimate_id' => $estimate_id,
            'accepted_quote_id' => $accepted_quote_id,
            'accepted_delivery_type' => $accepted_quote_type,
            'requested_date' => date('Y-m-d'),
            'status' => "pending",
            'delivery_data' => $delivery_response,
            'created_time' => $currentTimestamp,
            'updated_time' => $currentTimestamp,
        ];

        if (!empty($error_data)) {
            $delivery_data['error_data'] = json_encode($error_data);
        }

        if (!empty($error_data) && !empty($delivery_input_data)) {
            $delivery_data['delivery_input_data'] = $delivery_input_data;
        }

        DB::table('gw_deliveries')->insertGetId($delivery_data);

        return $delivery_data;
    }

    private function getQuoteByQuoteId(array $possibleDeliveries, $quote_id) {
        $GLOBALS['quote_id'] = $quote_id;

        $filtered_quotes = array_filter($possibleDeliveries, function ($posDelivery) {
            if (strcmp($GLOBALS['quote_id'], '0') === 0 && !isset($posDelivery['deliveryId'])) {
                return true;
            } else if (isset($posDelivery['deliveryId']) && strcmp($posDelivery['deliveryId'], $GLOBALS['quote_id']) == 0) {
                return true;
            } else if (isset($posDelivery['shipments'])) {
                $shipments = $posDelivery['shipments'];
                if (count($shipments) > 0) {
                    $legs = $shipments[0]['legs'];
                    if (count($legs) > 0) {
                        if (strcmp($GLOBALS['quote_id'], '0') === 0 && !isset($legs[0]['quoteId'])) {
                            return true;
                        } else if (isset($legs[0]['quoteId']) && strcmp($legs[0]['quoteId'], $GLOBALS['quote_id']) == 0) {
                            return true;
                        }
                    }
                }
            }

            return false;
        });

        return $filtered_quotes;
    }

    private function getQuoteDetailsByQuoteId($estimate_data, $quote_id) {
        $quote_details = null;

        if (!empty($estimate_data) && !empty($quote_id)) {
            $quote_data_json = $estimate_data['quotes_data'];

            $quote_data = json_decode($quote_data_json, true);

            $possibleDeliveries = $quote_data['possibleDeliveries'];

            if (!empty($possibleDeliveries) && count($possibleDeliveries) > 0) {
                $accepted_quotes = $this->getQuoteByQuoteId($possibleDeliveries, $quote_id);

                if (count($accepted_quotes) > 0) {
                    $quote_details = current($accepted_quotes);
                }
            }
        }

        return $quote_details;
    }

    private function getQuoteDetailsByIndex($estimate_data, $index, $ignoreValueDeliveryFlag = false) {
        $quote_details = null;

        if (!empty($estimate_data)) {
            $estimate_details = $this->getQuoteData($estimate_data, NULL, $ignoreValueDeliveryFlag);

            $options = $estimate_details['options'];

            if (!empty($options) && count($options) > 0) {
                $selectedOption = $options[$index - 1];

                $quote_id = $selectedOption['quote_id'];
                $quote_data_json = $estimate_data['quotes_data'];
                $quote_data = json_decode($quote_data_json, true);
                $possibleDeliveries = $quote_data['possibleDeliveries'];

                $selectedQuoteDetails = $this->getQuoteByQuoteId($possibleDeliveries, $quote_id);

                $quote_details = current($selectedQuoteDetails);
            }
        }

        return $quote_details;
    }

    /**
     * Insert of the estimation API  
     * Pass the bd-operation mode from config
     */
    private function createEstimates($payload) {
        $jsonPayload = json_encode($payload);

        $estimates = new EstimatesApi(Utils::getGatewayApiClient());

        return $estimates->createestimates($jsonPayload);
    }

    /**
     * Insert of the Deliveries API  
     */
    private function createDeliveries($payload) {
        $jsonPayload = json_encode($payload);

        $deliveries = new DeliveriesApi(Utils::getGatewayApiClient());

        return $deliveries->createdeliveries($jsonPayload);
    }

    private function prepareEmailData($data, $user_id, $estimate_id, $accepted_quote_id, $accepted_quote_type) {
        $company_id = $_REQUEST['AUTHUSERDATA']['gw_company_id'];
        $origin_loc = $data['request']['originLocation'];
        $origin_loc_contact = $origin_loc['contact'];

        $shipper_name = $origin_loc_contact['name'];

        $shipper_phone = null;
        if (!empty($origin_loc_contact['countryCode'])) {
            $shipper_phone = $origin_loc_contact['countryCode'];
        }

        if (!empty($shipper_phone)) {
            $shipper_phone = $shipper_phone . "-" . $origin_loc_contact['phone'];
        } else {
            $shipper_phone = $origin_loc_contact['phone'];
        }

        if (!empty($origin_loc_contact['extension'])) {
            $shipper_phone = $shipper_phone . "-" . $origin_loc_contact['extension'];
        }

        $shipment_address = $origin_loc['addressLine1'];

        if (isset($origin_loc['city']) && !empty($origin_loc['city'])) {
            $shipment_city = $origin_loc['city'];
        }

        $shipment_state = $origin_loc['state'];
        $shipment_country = $origin_loc['country'];
        $shipment_zip_code = $origin_loc['postalCode'];

        $shipment_location_type_data = $this->getLocationType($origin_loc);
        $shipment_location_type = $this->getLocationName($shipment_location_type_data);

        $shipper_origin_email = null;
        if (isset($origin_loc_contact['email']) && !empty($origin_loc_contact['email'])) {
            $shipper_origin_email = $origin_loc_contact['email'];
        }

        $shipper_origin_company_name = null;
        if (isset($origin_loc_contact['company']) && !empty($origin_loc_contact['company'])) {
            $shipper_origin_company_name = $origin_loc_contact['company'];
        }

        if (isset($shipment_city) && !empty($shipment_city)) {
            $shipment_origin_address = $shipment_address . " <br> " . $shipment_city . ", " . $shipment_state .
                    " , " . $shipment_country . " " . $shipment_zip_code;
        } else {
            $shipment_origin_address = $shipment_address . " <br> " . $shipment_state . ", " . $shipment_country .
                    " " . $shipment_zip_code;
        }

        $shipper_spl_notes = NULL;
        if (array_key_exists('shippingNotes', $origin_loc)) {
            $shipper_spl_notes = $origin_loc['shippingNotes'];
        }

        $dest_loc = $data['request']['destinationLocation'];
        $dest_loc_contact = $dest_loc['contact'];

        $shipper_delivery_name = $dest_loc_contact['name'];


        $shipper_delivery_phone = null;
        if (!empty($dest_loc_contact['countryCode'])) {
            $shipper_delivery_phone = $dest_loc_contact['countryCode'];
        }

        if (!empty($shipper_delivery_phone)) {
            $shipper_delivery_phone = $shipper_delivery_phone . "-" . $dest_loc_contact['phone'];
        } else {
            $shipper_delivery_phone = $dest_loc_contact['phone'];
        }

        if (!empty($dest_loc_contact['extension'])) {
            $shipper_delivery_phone = $shipper_delivery_phone . "-" . $dest_loc_contact['extension'];
        }

        $dest_address = $dest_loc['addressLine1'];
        if (isset($dest_loc['city']) && !empty($dest_loc['city'])) {
            $dest_city = $dest_loc['city'];
        }

        $dest_state = $dest_loc['state'];
        $dest_country = $dest_loc['country'];
        $dest_zip_code = $dest_loc['postalCode'];

        $dest_location_type_data = $this->getLocationType($dest_loc);
        $dest_location_type = $this->getLocationName($dest_location_type_data);
        $shipper_dest_email = null;
        if (isset($dest_loc_contact['email']) && !empty($dest_loc_contact['email'])) {
            $shipper_dest_email = $dest_loc_contact['email'];
        }

        $shipper_dest_company_name = null;
        if (isset($dest_loc_contact['company']) && !empty($dest_loc_contact['company'])) {
            $shipper_dest_company_name = $dest_loc_contact['company'];
        }

        if (isset($dest_city) && !empty($dest_city)) {
            $shipment_delivery_address = $dest_address . " <br> " . $dest_city . ", " . $dest_state . " , " .
                    $dest_country . " " . $dest_zip_code;
        } else {
            $shipment_delivery_address = $dest_address . " <br> " . $dest_state . ", " . $dest_country .
                    " " . $dest_zip_code;
        }

        $dest_spl_notes = NULL;
        if (array_key_exists('shippingNotes', $dest_loc)) {
            $dest_spl_notes = $dest_loc['shippingNotes'];
        }

        $shipment_quote = $data['request']['rateQuoted'];
        $shipment_confirmed_on = date("F j, Y");

        //Get the commodity details baed on the given estimate_id and user_id
        DB::setFetchMode(PDO::FETCH_ASSOC);

        $query = '';
        if (UserService::isSuperUser()) {
            $query = 'select estimate_id, status, expiry_date, requested_date, quotes_data from gw_estimates where company_id = '
                    . $company_id . " and estimate_id = '" . $estimate_id . "'";
        } else {
            $query = 'select estimate_id, status, expiry_date, requested_date, quotes_data from gw_estimates where user_id = '
                    . $user_id . " and estimate_id = '" . $estimate_id . "'";
        }

        $results = DB::select($query);

        DB::setFetchMode(PDO::FETCH_CLASS);

        $commodities = array();
        if (count($results) > 0) {
            $result = $results[0];
            $quote_data = json_decode($result['quotes_data'], true);

            $quote_freight_items_data = $quote_data['freightItems'];

            for ($i = 0; $i <= count($quote_freight_items_data) - 1; $i++) {
                $commodity_str = '<BR> Description: ' . $quote_freight_items_data[$i]['description'] . '<BR>';
                $commodity_str .= $quote_freight_items_data[$i]['quantity'] . ' ' . $quote_freight_items_data[$i]['unit'] . ' of ' .
                        $quote_freight_items_data[$i]['dimensions']['length'] . '" * ' . $quote_freight_items_data[$i]['dimensions']['width'] .
                        '" * ' . $quote_freight_items_data[$i]['dimensions']['height'] . '" at ' .
                        $quote_freight_items_data[$i]['weight']['weight'] . ' pounds' . '<BR>';
                $commodity_str .= 'Class code: ' . $quote_freight_items_data[$i]['classCode'];
                if (array_key_exists('nmfc', $quote_freight_items_data[$i]) && !empty($quote_freight_items_data[$i]['nmfc'])) {
                    $commodity_str = $commodity_str . ' NMFC code: ' . $quote_freight_items_data[$i]['nmfc'];
                }


                $commodities[] = $commodity_str;
            }
        }

        $possibleDeliveries = $quote_data['possibleDeliveries'];
        $options = $this->getAcceptedQuoteDetailsByQuoteId($possibleDeliveries, $accepted_quote_id);
        $transit_time = $options['transit_time'];

        $drop_off_location = $options['drop_off_location'];

        if (isset($drop_off_location) && !empty($drop_off_location)) {
            $drop_off_location['drop_address'] = $drop_off_location['addressLine1'] . " " . $drop_off_location['addressLine2'] . "<br> " . $drop_off_location['city'] . ", "
                    . $drop_off_location['state'] . ", " . $drop_off_location['postalCode'];

            if (array_key_exists('contact', $drop_off_location)) {
                if (array_key_exists('phone', $drop_off_location['contact'])) {
                    $drop_off_location['drop_phone'] = $drop_off_location['contact']['phone'];
                }
            }
        }
        $customer_reference_number = null;
        if (isset($data['request']['referenceNumber']) && !empty($data['request']['referenceNumber'])) {
            $customer_reference_number = $data['request']['referenceNumber'];
        }

        $templateData = [
            'name' => $_REQUEST['AUTHUSERDATA']['name'],
            'shipper_name' => $shipper_name,
            'shipper_phone' => $shipper_phone,
            'shipment_address' => $shipment_delivery_address,
            'shipment_origin_address' => $shipment_origin_address,
            'shipper_spl_notes' => $shipper_spl_notes,
            'shipper_delivery_name' => $shipper_delivery_name,
            'shipper_delivery_phone' => $shipper_delivery_phone,
            'shipment_delivery_address' => $shipment_delivery_address,
            'shipment_location_type' => ucfirst($shipment_location_type),
            'shipper_origin_email' => $shipper_origin_email,
            'shipper_origin_company_name' => $shipper_origin_company_name,
            'destination_location_type' => ucfirst($dest_location_type),
            'shipper_dest_email' => $shipper_dest_email,
            'shipper_dest_company_name' => $shipper_dest_company_name,
            'dest_spl_notes' => $dest_spl_notes,
            'commodities' => $commodities,
            'shipment_quote' => $shipment_quote,
            'shipment_confirmed_date' => $shipment_confirmed_on,
            'shipment_quote_id' => $estimate_id,
            'url' => getenv("APP_URL"),
            'drop_off_location' => $drop_off_location,
            'transit_time' => $transit_time,
            'customer_reference_number' => $customer_reference_number,
            'accepted_quote_type' => $accepted_quote_type
        ];

        return $templateData;
    }

    private function sendEmail($subject, $template, $templateData, $from, $to, $cc = NULL, $bcc = NULL, $attachmentName = NULL, $attachmentData = NULL) {
        $emailNotifier = new EmailNotifier($subject, $template, $from, $to, $cc, $bcc);

        if (!empty($attachmentName) && !empty($attachmentData)) {
            $emailNotifier->setAttachmentData($attachmentData);
            $emailNotifier->setAttachmentName($attachmentName);
        }

        $emailNotifier->send($templateData);
    }

    public function classCode(Request $request) {
        $input_data = $request->json()->all();

        if (isset($input_data)) {
            $rules = array(
                'dimensions' => 'required',
                'dimensions.height' => 'required',
                'dimensions.width' => 'required',
                'dimensions.length' => 'required',
                'dimensions.unit' => 'required',
                'weight' => 'required',
                'weight.weight' => 'required',
                'weight.unit' => 'required'
            );

            $validator = Validator::make($input_data, $rules);

            if ($validator->fails()) {
                // return the missing input
                $failureMsg = trans('messages.mandatory_head');
                $temp = $validator->errors()->all();

                $errorMessage = $failureMsg . '<br> ' . implode("<br>", $temp);
                $errorMessage = str_replace('dimensions.height', 'Height', $errorMessage);
                $errorMessage = str_replace('dimensions.width', 'Width', $errorMessage);
                $errorMessage = str_replace('dimensions.length', 'Length', $errorMessage);
                $errorMessage = str_replace('weight.weight', 'Weight', $errorMessage);

                return $this->logErrorAndSendResponse($errorMessage);
            }
        } else {
            $errorMessage = trans('messages.missing_payload');
            return $this->logErrorAndSendResponse($errorMessage);
        }

        $density_data = Utils::calculateCCode($input_data);

        if (array_key_exists('statusCode', $density_data) && $density_data['statusCode'] == 500) {
            return $this->sendJsonResponse($density_data, 500);
        }

        return $this->sendSuccessResponse($density_data);
    }

    private function checkQuoteAddress(array $input_address) {
        if (array_key_exists('city', $input_address)) {
            $input_address['city'] = strtolower($input_address['city']);
        }

        $input_address['state'] = strtolower($input_address['state']);
        $input_address['country'] = strtolower($input_address['country']);
        $input_address['zipCode'] = strtolower($input_address['postalCode']);

        unset($input_address['postalCode']);
        unset($input_address['isValidPostalCode']);
        unset($input_address['cities']);
        unset($input_address['location_type']);

        $validateResponse = Utils::validateAddressWithZip($input_address, array('US', 'CA'), true);

        return $validateResponse;
    }

    public function prepareAndSendFailedQuoteEmail($data, $originState, $destinationState, $failureReason, $estimate_id = NULL) {
        $user_id = $_REQUEST['AUTHUSERDATA']['gw_user_id'];
        $company_id = $_REQUEST['AUTHUSERDATA']['gw_company_id'];

        $origin_loc = $data['request']['originLocation'];

        if (isset($origin_loc['city']) && !empty($origin_loc['city'])) {
            $shipment_city = $origin_loc['city'];
        }

        $shipment_state = $originState; // State name in full form say California instead of CA
        $shipment_country = $origin_loc['country'];
        $shipment_zip_code = $origin_loc['postalCode'];

        $shipment_location_type_data = $this->getLocationType($origin_loc);
        $shipment_location_type = $this->getLocationName($shipment_location_type_data);
        $origin_loc_contact = $origin_loc['contact'];

        $shipper_origin_email = null;
        if (isset($origin_loc_contact['email']) && !empty($origin_loc_contact['email'])) {
            $shipper_origin_email = $origin_loc_contact['email'];
        }

        $shipper_origin_company_name = null;
        if (isset($origin_loc_contact['company']) && !empty(origin_loc_contact['company'])) {
            $shipper_origin_company_name = $origin_loc_contact['company'];
        }

        if (isset($shipment_city) && !empty($shipment_city)) {
            $shipment_origin_address = $shipment_city . ", " . $shipment_state .
                    ", " . $shipment_country . " " . $shipment_zip_code;
        } else {
            $shipment_origin_address = $shipment_state . ", " . $shipment_country .
                    " " . $shipment_zip_code;
        }

        $dest_loc = $data['request']['destinationLocation'];

        if (isset($dest_loc['city']) && !empty($dest_loc['city'])) {
            $dest_city = $dest_loc['city'];
        }

        $dest_state = $destinationState; // State name in full form say California instead of CA
        $dest_country = $dest_loc['country'];
        $dest_zip_code = $dest_loc['postalCode'];

        $dest_location_type_data = $this->getLocationType($dest_loc);
        $dest_location_type = $this->getLocationName($dest_location_type_data);
        $dest_loc_contact = $dest_loc['contact'];
        $shipper_dest_email = null;
        if (isset($dest_loc_contact['email']) && !empty($dest_loc_contact['email'])) {
            $shipper_dest_email = $dest_loc_contact['email'];
        }

        $shipper_dest_company_name = null;
        if (isset($dest_loc_contact['company']) && !empty($dest_loc_contact['company'])) {
            $shipper_dest_company_name = $dest_loc_contact['company'];
        }

        if (isset($dest_city) && !empty($dest_city)) {
            $shipment_delivery_address = $dest_city . " , " . $dest_state . " , " .
                    $dest_country . " " . $dest_zip_code;
        } else {
            $shipment_delivery_address = $dest_state . " , " . $dest_country .
                    " " . $dest_zip_code;
        }

        $shipment_requested_date = date("F j, Y");
        $commodities = array();

        $quote_freight_items_data = $data['request']['freightItems'];
        for ($i = 0; $i <= count($quote_freight_items_data) - 1; $i++) {
            $commodity_str = '<BR> Description: ' . $quote_freight_items_data[$i]['description'] . '<BR>';
            $commodity_str .= $quote_freight_items_data[$i]['quantity'] . ' ' . $quote_freight_items_data[$i]['unit'] . ' of ' .
                    $quote_freight_items_data[$i]['dimensions']['length'] . '" * ' . $quote_freight_items_data[$i]['dimensions']['width'] .
                    '" * ' . $quote_freight_items_data[$i]['dimensions']['height'] . '" at ' .
                    $quote_freight_items_data[$i]['weight']['weight'] . ' pounds' . '<BR>';
            $commodity_str .= 'Class code: ' . $quote_freight_items_data[$i]['classCode'];
            if (array_key_exists('nmfc', $quote_freight_items_data[$i]) && !empty($quote_freight_items_data[$i]['nmfc'])) {

                $commodity_str = $commodity_str . ' NMFC code: ' . $quote_freight_items_data[$i]['nmfc'];
            }

            $commodities[] = $commodity_str;
        }

        $userCompanyInfo = UserService::getUserAsArrayWithCompanyInfo($user_id);

        $customer_reference_number = null;
        if (isset($data['request']['referenceNumber']) && !empty($data['request']['referenceNumber'])) {
            $customer_reference_number = $data['request']['referenceNumber'];
        }

        $templateData = [
            'name' => $_REQUEST['AUTHUSERDATA']['name'],
            'shipment_quote_id' => $estimate_id,
            'quote_failure_reason' => $failureReason,
            'shipper_full_name' => $userCompanyInfo['first_name'] . ' ' . $userCompanyInfo['last_name'],
            'shipper_name' => $userCompanyInfo['first_name'] . ' ' . $userCompanyInfo['last_name'],
            'shipper_email' => $userCompanyInfo['email'],
            'shipper_phone' => $userCompanyInfo['phone_number'],
            'shipper_company' => $userCompanyInfo['legal_name'],
            'shipment_address' => $shipment_delivery_address,
            'shipment_location_type' => ucfirst($shipment_location_type),
            'shipper_origin_email' => $shipper_origin_email,
            'shipper_origin_company_name' => $shipper_origin_company_name,
            'shipper_dest_email' => $shipper_dest_email,
            'shipper_dest_company_name' => $shipper_dest_company_name,
            'destination_location_type' => ucfirst($dest_location_type),
            'shipment_origin_address' => $shipment_origin_address,
            'shipment_delivery_address' => $shipment_delivery_address,
            'customer_reference_number' => $customer_reference_number,
            'commodities' => $commodities,
        ];

        $template = 'gateway_supplier_request_quote_fail';

        $subject = 'Manual Quote: From ' . $userCompanyInfo['first_name'] .
                ' ' . $userCompanyInfo['last_name'] . ' at ' . $userCompanyInfo['name'] . ' on '
                . $shipment_requested_date . ' for destination ' . $dest_zip_code;

        $to = NULL;
        $cc = NULL;
        $bcc = NULL;
        $logged_in_email = $_REQUEST['AUTHUSERDATA']['email'];
        if (empty(getenv("MAIL_TO_MANUAL_QUOTE"))) {
            $to = $logged_in_email;
        } else {
            $to = explode(',', getenv("MAIL_TO_MANUAL_QUOTE"));
        }

        if (!empty(getenv("MAIL_CC"))) {
            $cc = explode(',', getenv("MAIL_CC"));
        }

        if (!empty(getenv("MAIL_BCC"))) {
            $bcc = explode(',', getenv("MAIL_BCC"));
        }

        $from = getenv("MAIL_FROM_MANUAL_QUOTE");
        $this->sendEmail($subject, $template, $templateData, $from, $to, $cc, $bcc);
    }

    private function uploadShippingLabel($deliveryData) {
        if (!empty($deliveryData) && !is_null($deliveryData)) {
            if (!empty($deliveryData->tenderId) && !is_null($deliveryData->tenderId)) {
                $s3Client = AwsTools::getS3Client('us-west-2');

                $docArray = json_decode(json_encode($deliveryData->delivery->shipments[0]->legs[0]->documents), true);

                if (count($docArray) > 0) {
                    foreach ($docArray as $key => $value) {
                        $labelId = $value;

                        foreach ($key as $key1) {
                            $data = base64_decode($tenderId['label']['data']);

                            //$shipment_id = json_decode(json_encode($deliveryData->delivery->shipments[0]->shipmentId));
                            //$shipment_id = str_replace('-', '', $shipment_id);
                            //$shipment_id = $shipment_id . ":" . $tenderId;

                            $app_env = $_ENV['ENVIRONMENT'];
                            $deliveryFile = $labelId . '.pdf';
                            $filePath = 'shipping-labels/' . $app_env . '/' . $deliveryFile;

                            $homeDir = __DIR__ . '/../../../storage/' . $deliveryFile;
                            file_put_contents($homeDir, $data);

                            $result = $s3Client->putObject(array(
                                'Bucket' => 'bd-gateway',
                                'Key' => $filePath,
                                'Body' => $data
                            ));
                        }
                    }
                }
            }
        }
    }

}
