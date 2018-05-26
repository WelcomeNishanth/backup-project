<?php
namespace App\Services;

use Invoicing\Client\Api\InvoicesApi;
use \Invoicing\Client\Configuration;
use \Invoicing\Client\ApiClient;

class InvoiceService {

    public static function invoicesGet($ns_customer_id, $api_key, $page_size, $search_id, $page_index) {
        $data = null;

        $api_uri = getenv("INVOICING_API_URL");

        $config = new Configuration();
        $config->setHost($api_uri);

        $apiClient = new ApiClient($config);
        $apiEndpoint = new InvoicesApi($apiClient);

        $response = $apiEndpoint->invoiceGetAll($ns_customer_id, null, $page_size, $search_id, $page_index);

        if (!empty($response) && $response->statusCode === 200) {
            $responseData = $response->data;
            $totalRecords = $responseData->totalRecords;

            $data = array("totalRecords" => $totalRecords,
                "pageSize" => $responseData->pageSize,
                "totalPages" => $responseData->totalPages,
                "pageIndex" => $responseData->pageIndex,
                "searchId" => $responseData->searchId);

            if ($totalRecords > 0) {
                $records = array();
                foreach ($responseData->recordList as $responseRecord) {
                    $record = array(
                        "invoiceNumber" => $responseRecord->tranId,
                        "orderNumber" => $responseRecord->otherRefNum,
                        "invoiceDate" => date('n/j/Y', strtotime($responseRecord->tranDate)),
                        "memo" => $responseRecord->memo,
                        "dueDate" => date('n/j/Y', strtotime($responseRecord->dueDate)),
                        "shipDate" => date('n/j/Y', strtotime($responseRecord->shipDate)),
                        "trackingNumbers" => $responseRecord->trackingNumbers,
                        "currencyName" => $responseRecord->currencyName,
                        "total" => $responseRecord->total,
                        "subTotal" => $responseRecord->subTotal,
                        "status" => $responseRecord->status
                    );

                    if (!empty($responseRecord->billingAddress)) {
                        $record["billingAddress"] = array(
                            "country" => $responseRecord->billingAddress->country,
                            "contact" => $responseRecord->billingAddress->addressee,
                            "phone" => $responseRecord->billingAddress->addrPhone,
                            "addressLine1" => $responseRecord->billingAddress->addr1,
                            "addressLine2" => $responseRecord->billingAddress->addr2,
                            "city" => $responseRecord->billingAddress->city,
                            "state" => $responseRecord->billingAddress->state,
                            "zipCode" => $responseRecord->billingAddress->zip);
                    }

                    $responseItems = $responseRecord->itemList->item; // add condition

                    $items = array();
                    $gst = 0;
                    $pst = 0;
                    foreach ($responseItems as $responseItem) {
                        $item = array(
                            "name" => $responseItem->item->name,
                            "description" => $responseItem->description,
                            "amount" => $responseItem->amount,
                            "quantity" => $responseItem->quantity
                        );

                        if (!empty($responseItem->taxRate1)) {
                            $gst = $gst + ($responseItem->amount * $responseItem->taxRate1) / 100;
                        }

                        if (!empty($responseItem->taxRate2)) {
                            $pst = $pst + ($responseItem->amount * $responseItem->taxRate2) / 100;
                        }

                        $items[] = $item;
                    }

                    $record['items'] = $items;

                    $record['totalTax'] = $gst;
                    $record['totalPst'] = $pst;

                    $records[] = $record;
                }
                $data['records'] = $records;
            }
        } else {
            $data = array("totalRecords" => 0,
                "pageSize" => 0,
                "totalPages" => 0,
                "pageIndex" => 0,
                "searchId" => null,
                "records" => null);
        }

        return $data;
    }

}
