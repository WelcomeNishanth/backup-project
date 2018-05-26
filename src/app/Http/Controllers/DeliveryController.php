<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Services\UserService;
use \App\Services\DeliveryService;

class DeliveryController extends Controller {

    /**
     * Get the list of deliveries
     * 
     * @param Request $request
     * @return type
     */
    public function deliveries(Request $request) {
        $company_id = $_REQUEST['AUTHUSERDATA']['gw_company_id'];
        $user_id = $_REQUEST['AUTHUSERDATA']['gw_user_id'];

        try {

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
                $sort_by = 'desc';
            }

            $totalCount = null;
            $totalPages = null;
            $isSuperUser = UserService::isSuperUser();

            if (empty($search_id) || empty($offset)) {
                $totalCount = DeliveryService::getDeliveriesCount($user_id, $company_id, $isSuperUser);
                $totalPages = ceil($totalCount / $page_size);
            }

            if (empty($search_id)) {
                $search_id = time();
            }

            $deliveries_data = DeliveryService::getDeliveries($user_id, $company_id, $isSuperUser, $search_id, $offset, $page_size, $sort_key, $sort_by);

            return $this->sendSuccessResponse(array('delivery' => $deliveries_data,
                        'searchId' => $search_id,
                        'offset' => $offset,
                        'pageSize' => $page_size,
                        'totalCount' => $totalCount,
                        'totalPages' => $totalPages
            ));
        } catch (\Exception $exception) {
            $errorMessage = trans('messages.deliveries_request_failed'); // add it in messages.ini
            return $this->logExceptionAndSendResponse($exception, $errorMessage);
        }
    }

    /**
     * Get the delivery
     * 
     * @param Request $request
     * @return type
     */
    public function delivery(Request $request) {
        $company_id = $_REQUEST['AUTHUSERDATA']['gw_company_id'];
        $user_id = $_REQUEST['AUTHUSERDATA']['gw_user_id'];

        $delivery_id = $request->header('deliveryid');

        if (!isset($delivery_id) || empty($delivery_id)) {
            $errorMessage = trans('messages.missing_single_field', ['name' => 'delivery_id']);
            return $this->logErrorAndSendResponse($errorMessage);
        }

        try {
            $isSuperUser = UserService::isSuperUser();
            $delivery_data = DeliveryService::getDeliveryById($delivery_id, $user_id, $company_id, $isSuperUser);

            if (!empty($delivery_data)) {
                return $this->sendSuccessResponse(array('delivery' => $delivery_data));
            } else {
                $errorMessage = trans('messages.delivery_not_found');
                return $this->logErrorAndSendResponse($errorMessage);
            }
        } catch (\Exception $exception) {
            $errorMessage = trans('messages.delivery_request_failed');
            return $this->logExceptionAndSendResponse($exception, $errorMessage);
        }
    }

    /**
     * Get the delivery
     * 
     * @param Request $request
     * @return type
     */
    public function trackOrder(Request $request) {
        $delivery_id = $request->header('deliveryid');

        if (!isset($delivery_id) || empty($delivery_id)) {
            $errorMessage = trans('messages.missing_single_field', ['name' => 'delivery_id']);
            return $this->logErrorAndSendResponse($errorMessage);
        }

        try {
            $delivery_data = DeliveryService::getDeliveryById($delivery_id);

            if (!empty($delivery_data)) {
                return $this->sendSuccessResponse(array('delivery' => $delivery_data));
            } else {
                $errorMessage = trans('messages.delivery_not_found');
                return $this->logErrorAndSendResponse($errorMessage);
            }
        } catch (\Exception $exception) {
            $errorMessage = trans('messages.delivery_request_failed');
            return $this->logExceptionAndSendResponse($exception, $errorMessage);
        }
    }

}
