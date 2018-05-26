<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use \PDO;
use \Gateway\Client\Api\TrackingApi;

class DeliveryService {

    public static function getDeliveries($user_id, $company_id, $isSuperUser, $search_id, $offset, $page_size, $sort_key, $sort_by) {
        DB::setFetchMode(PDO::FETCH_ASSOC);

        if ($isSuperUser) {
            $query = "select user_id, delivery_id, status, estimate_id, accepted_quote_id, accepted_delivery_type, delivery_data from gw_deliveries "
                    . "where company_id = " . $company_id . " and error_data IS NULL and delivery_data IS NOT NULL and accepted_quote_id != 'crs'";
        } else {
            $query = "select user_id, delivery_id, status, estimate_id, accepted_quote_id, accepted_delivery_type, delivery_data from gw_deliveries "
                    . "where user_id = " . $user_id . " and error_data IS NULL and delivery_data IS NOT NULL and accepted_quote_id != 'crs'";
        }

        if (!empty($search_id)) {
            $query .= ' and unix_timestamp(updated_time) <= ' . $search_id;
        }

        $query .= ' order by ' . $sort_key . ' ' . $sort_by . ' limit ' . $offset . " ," . $page_size;

        $results = DB::select($query);

        DB::setFetchMode(PDO::FETCH_CLASS);

        $deliveries_data = null;
        if (count($results) > 0) {
            $deliveries_data = array();

            foreach ($results as $result) {
                if (!empty($result['delivery_data'])) {
                    $delivery_data = self::prepareDeliveryData($result);
                    if (!empty($delivery_data)) {
                        $deliveries_data[] = $delivery_data;
                    }
                }
            }
        }

        return $deliveries_data;
    }

    private static function prepareDeliveryData($delivery_data, $single_item = false) {
        $deliveryId = $delivery_data['delivery_id'];
        $delivery_quote_data = json_decode($delivery_data['delivery_data'], true);

        $deliveryData = null;
        if (gettype($delivery_quote_data) == 'array' && array_key_exists('delivery', $delivery_quote_data)) {
            $delivery = $delivery_quote_data['delivery'];

            $shipment = $delivery['shipments'][0];
            $shipmentLegs = $shipment["legs"];

            $lastMileLeg = self::getLastMileLeg($shipmentLegs);

            $desiredStartDate = $delivery_quote_data['desiredStartDate'];
            $estimatedPickedUpDate = date("F j, Y", strtotime($desiredStartDate));

            $leadTimeInDays = $shipment['estimatedLeadTimeDays'];
            $estimatedDeliveryDate = date("F j, Y", strtotime($desiredStartDate . ' +' . $leadTimeInDays . ' days'));

            $originLocation = $delivery['originLocation'];
            if (isset($originLocation['locationType'])) {
                $originLocation['locationType'] = ucfirst($originLocation['locationType']);
            }
            $originNotes = null;
            if (array_key_exists('shippingNotes', $originLocation)) {
                $originNotes = $originLocation['shippingNotes'];
            }

            $trackingInfo = self::getTrackingInfo($deliveryId);
            $currentStatusInfo = self::getCurrentStatus($trackingInfo);
            $trackingLog = self::getTrackingLog($trackingInfo);

            $latestStatus = null;
            if (!empty($trackingLog) && count($trackingLog) > 0 && count($trackingLog[0]['events']) > 0) {
                $latestStatus = $trackingLog[0]['events'][0]['statusCode'];
                if (!empty($latestStatus) && strcmp($currentStatusInfo['status'], $latestStatus) !== 0) {
                    $currentStatusInfo['status'] = $latestStatus;
                }
            }

            $actualPickedUpDate = null;
            if (strcmp($currentStatusInfo["status"], "PENDING PICKUP") !== 0 && !empty($trackingLog) && count($trackingLog) > 0) {
                $actualPickedUpDate = $trackingLog[count($trackingLog) - 1]['date'];
            }

            $actualDeliveryDate = null;
            if (strcmp($currentStatusInfo["status"], "DELIVERED") === 0) {
                $actualDeliveryDate = date("F j, Y", strtotime($currentStatusInfo["timestamp"]));
            }

            $carrierInfo = self::getLegCarrierInfo($lastMileLeg);
            
            $freightItems = array();
            foreach ($lastMileLeg['freightItems'] as $freightItem) {
                $freightItem["estimatedPickedUpDate"] = $estimatedPickedUpDate;
                $freightItem["estimatedDeliveryDate"] = $estimatedDeliveryDate;
                $freightItem["pickedUpDate"] = $actualPickedUpDate;
                $freightItem["transitStatus"] = $currentStatusInfo["status"];
                $freightItem["statusTimestamp"] = $currentStatusInfo["timestamp"];
                $freightItem["deliveredDate"] = $actualDeliveryDate;

                if ($single_item) {
                    $freightItem["originNotes"] = $originNotes;
                    $freightItem["originLocation"] = $originLocation;
                    $freightItem['trackingInfo'] = $trackingLog;

                    // if (count($shipmentLegs) == 1) {
                    $freightItem["carrier"] = $carrierInfo;
                    /* } else { // decided not to show the resupply and flow-through carrier details
                      $freightItem["pickUpCarrier"] = self::getLegCarrierInfo($lastMileLeg);
                      $freightItem["deliveryCarrier"] = self::getLegCarrierInfo(end($shipmentLegs));
                      } */
                }

                $freightItems[] = $freightItem;
            }

            $destinationLocation = $delivery['destinationLocation'];
            if (isset($destinationLocation['locationType'])) {
                $destinationLocation['locationType'] = ucfirst($destinationLocation['locationType']);
            }
            $destinationNotes = null;
            if (array_key_exists('shippingNotes', $destinationLocation)) {
                $destinationNotes = $destinationLocation['shippingNotes'];
            }

            $deliveryData = array();
            $deliveryData["deliveryId"] = $deliveryId;

            $refNumber = null;
            if (isset($delivery_quote_data['delivery']['referenceIds']['referenceNumber']) && 
                gettype($delivery_quote_data['delivery']['referenceIds']['referenceNumber']) === 'string') {
                    $refNumber = $delivery_quote_data['delivery']['referenceIds']['referenceNumber'];
            }
            
            $deliveryData["referenceNumber"] = $refNumber;

            $deliveryData["destinationLocation"] = $destinationLocation;
            $deliveryData["shippingNotes"]["originLocation"] = $originNotes;
            $deliveryData["shippingNotes"]["destinationLocation"] = $destinationNotes;
            $deliveryData["freightItems"] = $freightItems;
            $deliveryData["quote_id"] = $delivery_data["estimate_id"];
            $deliveryData["drop_off_location"] = self::getDropOffLocation($shipmentLegs);
            $deliveryData["shipping_option"] = $delivery_data["accepted_delivery_type"];

            if ($single_item) {
                $deliveryData["orderTotal"] = self::getOrderRate($delivery);

                $pickUpServices = null;
                $deliveryServices = null;
                if (self::isLiftgate($lastMileLeg)) {
                    if (self::isLiftgateApplicable($originLocation)) {
                        $pickUpServices = 'Liftgate Pickup';
                    }

                    if (self::isLiftgateApplicable($destinationLocation)) {
                        $pickUpServices = 'Liftgate Delivery';
                    }
                }

                $deliveryData["pickUpServices"] = $pickUpServices;
                $deliveryData["deliveryServices"] = $deliveryServices;
            }
        }

        return $deliveryData;
    }

    private static function getDropOffLocation($legs) {
        $dropOffLocation = null;
        if (array_key_exists('dropOffLocation', $legs[0])) {
            $dropOffLocation = $legs[0]['dropOffLocation'];
        } else if (array_key_exists('dropoffLocation', $legs[0])) {
            $dropOffLocation = $legs[0]['dropoffLocation'];
        }

        $dropOffLocationAsString = null;
        if (!empty($dropOffLocation)) {
            $dropOffLocationAsString = "";
            if (isset($dropOffLocation['addressLine1'])) {
                $dropOffLocationAsString .= $dropOffLocation['addressLine1'];
            }
            if (isset($dropOffLocation['addressLine2'])) {
                $dropOffLocationAsString .= " " . $dropOffLocation['addressLine2'];
            }
            if (isset($dropOffLocation['city'])) {
                $dropOffLocationAsString .= ", " . $dropOffLocation['city'];
            }
            if (isset($dropOffLocation['state'])) {
                $dropOffLocationAsString .= ", " . $dropOffLocation['state'];
            }
            if (isset($dropOffLocation['postalCode'])) {
                $dropOffLocationAsString .= ", " . $dropOffLocation['postalCode'];
            }

            if (array_key_exists('contact', $dropOffLocation)) {
                if (array_key_exists('phone', $dropOffLocation['contact'])) {
                    $dropOffLocationAsString .= ". Phone number: " . $dropOffLocation['contact']['phone'];
                }
            }
        }

        return $dropOffLocationAsString;
    }

    private static function getTrackingInfo($deliveries_id) {
        $apiClient = Utils::getGatewayApiClient();
        $trackingApi = new TrackingApi($apiClient);

        return $trackingApi->gettrackings($deliveries_id);
    }

    private static function getCurrentStatus($trackingInfo) {
        $currentStatus = "PENDING PICKUP";
        $timestamp = null;
        if (!empty($trackingInfo) && isset($trackingInfo->currentEvent)) {
            $currentEvent = $trackingInfo->currentEvent;

            if (isset($currentEvent->action)) {
                $action = $currentEvent->action;
                $currentStatus = self::getStatusName($action);
                $timestamp = date("F j, Y h:i A T", strtotime($currentEvent->timestamp));
            }
        }

        return array("status" => $currentStatus, "timestamp" => $timestamp);
    }

    private static function getStatusName($action) {
        $currentStatus = "PENDING PICKUP";

        if (strcmp($action, "tendered") == 0) {
            $currentStatus = "PENDING PICKUP";
        } else if (strcmp($action, "scheduled") == 0) {
            $currentStatus = "PENDING PICKUP";
        } else if (strcmp($action, "in_transit") == 0) {
            $currentStatus = "IN TRANSIT";
        } else if (strcmp($action, "out_for_delivery") == 0) {
            $currentStatus = "IN TRANSIT";
        } else if (strcmp($action, "delivered") == 0) {
            $currentStatus = "DELIVERED";
        } else if (!empty($action)) {
            $action = ucfirst($action);
        }

        return $currentStatus;
    }

    private static function getTrackingLog($trackingInfo) {
        $trackingLog = null;
        if (!empty($trackingInfo)) {
            if (isset($trackingInfo->events) && count($trackingInfo->events) > 0) {
                $trackingInfoPerDay = array();
                $events = $trackingInfo->events;

                foreach ($events as $event) {
                    if (strcmp(strtolower($event->action), "tendered") == 0) {
                        continue;
                    }

                    $event_timestamp = strtotime($event->timestamp);
                    $trackingDate = date("F j, Y", $event_timestamp);
                    $trackingTime = date("h:i A T", $event_timestamp);

                    if (!array_key_exists($trackingDate, $trackingInfoPerDay)) {
                        $trackingInfoPerDay[$trackingDate] = array();
                    }

                    $trackingInfoPerDay[$trackingDate][] = array("time" => $trackingTime,
                        "status" => self::getEventStatus($event->action),
                        "statusCode" => self::getStatusName($event->action),
                        "location" => $event->location,
                        "timestamp" => $event_timestamp);
                }

                $trackingLog = array();
                foreach ($trackingInfoPerDay as $date => $events) {
                    usort($events, function ($event1, $event2) {
                        $date1 = $event1["timestamp"];
                        $date2 = $event2["timestamp"];

                        if ($date1 < $date2) {
                            return -1;
                        } else if ($date1 > $date2) {
                            return 1;
                        } else {
                            return 0;
                        }
                    });

                    $trackingLog[] = array("date" => $date,
                        "events" => $events);
                }

                usort($trackingLog, function ($trackInfo1, $trackInfo2) {
                    $date1 = strtotime($trackInfo1["date"]);
                    $date2 = strtotime($trackInfo2["date"]);
                    if ($date1 < $date2) {
                        return 1;
                    } else if ($date1 > $date2) {
                        return -1;
                    } else {
                        return 0;
                    }
                });
            }
        }

        return $trackingLog;
    }

    private static function getEventStatus($action) {
        $eventStatus = "Pending Pickup";

        if (!empty($action)) {
            $action = str_replace("_", " ", $action);
            $eventStatus = ucfirst($action);
        }

        return $eventStatus;
    }

    private static function isLiftgateApplicable($location) {
        $isLiftgateApplicable = true;

        if (!empty($location)) {
            $locationType = null;

            if (!empty($location) && array_key_exists('information', $location) &&
                    array_key_exists('locationType', $location['information']) && array_key_exists('value', $location['information']['locationType'])) {
                $value = $location['information']['locationType']['value'];
                $dataType = gettype($value);
                if ($dataType === 'array' && count($value) > 0) {
                    $locationType = $value[0];
                    if (strcmp("commercial", $locationType) == 0) {
                        $dockType = self::getDockType($location);
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

            if (!empty($locationType)) {
                if (strcmp($locationType, "commercial-hasdock") == 0 || strcmp($locationType, "commercial") == 0) {
                    $isLiftgateApplicable = false;
                }
            }
        }

        return $isLiftgateApplicable;
    }

    private static function getDockType($location) {
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

    private static function getOrderRate($delivery) {
        $order_rate = 0;

        if (isset($delivery['estimatedTotal'])) {
            $order_rate = $delivery['estimatedTotal'];
        } else if (isset($delivery['totalQuote'])) {
            $order_rate = $delivery['totalQuote'];
        } else if (isset($delivery['totalEstimate'])) {
            $order_rate = $delivery['totalEstimate'];
        }

        return $order_rate;
    }

    private static function getLegCarrierInfo($shipmentLeg) {
        return array(
            "name" => $shipmentLeg['carrierName'],
            "phone" => "",
            "email" => ""
        );
    }

    private static function getLastMileLeg($legs) {
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

    private static function isLiftgate($lastMileLeg) {
        $isLiftGate = false;

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

    public static function getDeliveriesCount($user_id, $company_id, $isSuperUser) {
        DB::setFetchMode(PDO::FETCH_ASSOC);

        $totalCount = 0;
        if ($isSuperUser) {
            $countQuery = "select count(1) from gw_deliveries where company_id = " . $company_id .
                    " and error_data IS NULL and delivery_data IS NOT NULL and accepted_quote_id != 'crs'";
        } else {
            $countQuery = "select count(1) from gw_deliveries where user_id = " . $user_id .
                    " and error_data IS NULL and delivery_data IS NOT NULL and accepted_quote_id != 'crs'";
        }

        $delivery_results = DB::select($countQuery);

        DB::setFetchMode(PDO::FETCH_CLASS);
        if (count($delivery_results) > 0) {
            $totalCount = $delivery_results[0]['count(1)'];
        }

        return $totalCount;
    }

    public static function getDeliveryById($deliveryId, $user_id = NULL, $company_id = NULL, $isSuperUser = NULL) {
        DB::setFetchMode(PDO::FETCH_ASSOC);

        if (is_null($user_id) && is_null($company_id) && is_null($isSuperUser)) {
            $query = "select user_id, delivery_id, status, estimate_id, accepted_quote_id, accepted_delivery_type, delivery_data from gw_deliveries "
                    . "where delivery_id = '" . $deliveryId . "'";
        } else if ($isSuperUser) {
            $query = "select user_id, delivery_id, status, estimate_id, accepted_quote_id, accepted_delivery_type, delivery_data from gw_deliveries "
                    . "where company_id = " . $company_id . " and delivery_id = '" . $deliveryId . "'";
        } else {
            $query = "select user_id, delivery_id, status, estimate_id, accepted_quote_id, accepted_delivery_type, delivery_data from gw_deliveries "
                    . "where user_id = " . $user_id . " and delivery_id = '" . $deliveryId . "'";
        }

        $results = DB::select($query);

        DB::setFetchMode(PDO::FETCH_CLASS);

        $delivery_data = null;
        if (count($results) > 0) {
            $result = $results[0];
            if (!empty($result['delivery_data'])) {
                $delivery_data = self::prepareDeliveryData($result, true);
            }
        }

        return $delivery_data;
    }

}
