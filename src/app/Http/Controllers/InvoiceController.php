<?php

namespace App\Http\Controllers;

use \Invoicing\Client\ApiException;
use Illuminate\Http\Request;
use \App\Services\CompanyService;
use \App\Services\InvoiceService;

class InvoiceController extends Controller {

    public function getInvoices(Request $request) {
        $company_id = $_REQUEST['AUTHUSERDATA']['gw_company_id'];

        try {
            $input_data = $request->json()->all();

            $company = CompanyService::getCompanyById($company_id);
            $ns_customer_id = $company->ns_customer_id;

            $page_size = 10;
            if (array_key_exists('limit', $input_data)) {
                $page_size = $input_data['limit'];
            }

            $search_id = null;
            if (array_key_exists('searchId', $input_data)) {
                $search_id = $input_data['searchId'];
            }

            $page_index = 1;
            if (array_key_exists('pageNo', $input_data)) {
                $page_index = $input_data['pageNo'];
            }

            $data = InvoiceService::invoicesGet($ns_customer_id, null, $page_size, $search_id, $page_index);

            return $this->sendSuccessResponse($data);
        } catch (ApiException $apiException) {
            $apiReponseObject = $apiException->getResponseBody();
            $devMsg = json_encode($apiReponseObject);

            $errorMessage = trans('messages.invoices_get_error');

            return $this->logExceptionAndSendResponse($apiException, $errorMessage, $devMsg);
        } catch (\Exception $exception) {
            $errorMessage = trans('messages.invoices_get_error');

            return $this->logExceptionAndSendResponse($exception, $errorMessage);
        }
    }

}
