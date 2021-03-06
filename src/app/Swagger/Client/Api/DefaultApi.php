<?php
/**
 * DefaultApi
 * PHP version 5
 *
 * @category Class
 * @package  Swagger\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * Delivery Gateway V2
 *
 * With the Delivery Gateway, you can initiate delivery through the warehouse and satellite network.
 *
 * OpenAPI spec version: 1.0
 * 
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 *
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace App\Swagger\Client\Api;

use App\Swagger\Client\ApiClient;
use App\Swagger\Client\ApiException;
use App\Swagger\Client\Configuration;
use App\Swagger\Client\ObjectSerializer;

/**
 * DefaultApi Class Doc Comment
 *
 * @category Class
 * @package  Swagger\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class DefaultApi
{
    /**
     * API Client
     *
     * @var \Swagger\Client\ApiClient instance of the ApiClient
     */
    protected $apiClient;

    /**
     * Constructor
     *
     * @param \Swagger\Client\ApiClient|null $apiClient The api client to use
     */
    public function __construct(ApiClient $apiClient = null)
    {
        if ($apiClient === null) {
            $apiClient = new ApiClient();
        }

        $this->apiClient = $apiClient;
    }

    /**
     * Get API client
     *
     * @return \Swagger\Client\ApiClient get the API client
     */
    public function getApiClient()
    {
        return $this->apiClient;
    }

    /**
     * Set the API client
     *
     * @param \Swagger\Client\ApiClient $apiClient set the API client
     *
     * @return DefaultApi
     */
    public function setApiClient(\Swagger\Client\ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
        return $this;
    }

    /**
     * Operation calculateDensity
     *
     * 
     *
     * @param string $subscription_key subscription key in url (optional)
     * @param string $bd_operation_mode Determines if the call is to run in a test development mode or a live production mode. (optional)
     * @param string $ocp_apim_subscription_key subscription key in header (optional)
     * @throws \Swagger\Client\ApiException on non-2xx response
     * @return void
     */
    public function calculateDensity($subscription_key = null, $bd_operation_mode = null, $ocp_apim_subscription_key = null, $productSpec = null)
    {
        list($response) = $this->calculateDensityWithHttpInfo($subscription_key, $bd_operation_mode, $ocp_apim_subscription_key, $productSpec);
        return $response;
    }

    /**
     * Operation calculateDensityWithHttpInfo
     *
     * 
     *
     * @param string $subscription_key subscription key in url (optional)
     * @param string $bd_operation_mode Determines if the call is to run in a test development mode or a live production mode. (optional)
     * @param string $ocp_apim_subscription_key subscription key in header (optional)
     * @throws \Swagger\Client\ApiException on non-2xx response
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     */
    public function calculateDensityWithHttpInfo($subscription_key = null, $bd_operation_mode = null, $ocp_apim_subscription_key = null, $productSpec = null)
    {
        // parse inputs
        $resourcePath = "/density";
        $httpBody = '';
        $queryParams = [];
        $headerParams = [];
        $formParams = [];
        $_header_accept = $this->apiClient->selectHeaderAccept([]);
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(['application/json']);

        // query params
        if ($subscription_key !== null) {
            $queryParams['subscription-key'] = $this->apiClient->getSerializer()->toQueryValue($subscription_key);
        }
        // header params
        if ($bd_operation_mode !== null) {
            $headerParams['BD-Operation-Mode'] = $this->apiClient->getSerializer()->toHeaderValue($bd_operation_mode);
        }
        // header params
        if ($ocp_apim_subscription_key !== null) {
            $headerParams['Ocp-Apim-Subscription-Key'] = $this->apiClient->getSerializer()->toHeaderValue($ocp_apim_subscription_key);
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);
        // body params
        
        $_tempBody = null;
        if (isset($productSpec)) {
            $_tempBody = $productSpec;
        }
        
        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath,
                'POST',
                $queryParams,
                $httpBody,
                $headerParams,
                null,
                '/density'
            );

            return [$response, $statusCode, $httpHeader];
        } catch (ApiException $e) {
            switch ($e->getCode()) {
            }

            throw $e;
        }
    }

    /**
     * Operation deliveriesCreate
     *
     * 
     *
     * @param string $subscription_key subscription key in url (optional)
     * @param string $bd_operation_mode Determines if the call is to run in a test development mode or a live production mode. (optional, default to live)
     * @param string $ocp_apim_subscription_key subscription key in header (optional)
     * @param \Swagger\Client\Model\Deliveries $deliveries Data used to create a new deliveries (optional)
     * @throws \Swagger\Client\ApiException on non-2xx response
     * @return void
     */
    public function deliveriesCreate($subscription_key = null, $bd_operation_mode = null, $ocp_apim_subscription_key = null, $deliveries = null)
    {
        list($response) = $this->deliveriesCreateWithHttpInfo($subscription_key, $bd_operation_mode, $ocp_apim_subscription_key, $deliveries);
        return $response;
    }

    /**
     * Operation deliveriesCreateWithHttpInfo
     *
     * 
     *
     * @param string $subscription_key subscription key in url (optional)
     * @param string $bd_operation_mode Determines if the call is to run in a test development mode or a live production mode. (optional, default to live)
     * @param string $ocp_apim_subscription_key subscription key in header (optional)
     * @param \Swagger\Client\Model\Deliveries $deliveries Data used to create a new deliveries (optional)
     * @throws \Swagger\Client\ApiException on non-2xx response
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     */
    public function deliveriesCreateWithHttpInfo($subscription_key = null, $bd_operation_mode = null, $ocp_apim_subscription_key = null, $deliveries = null)
    {
        // parse inputs
        $resourcePath = "/deliveries";
        $httpBody = '';
        $queryParams = [];
        $headerParams = [];
        $formParams = [];
        $_header_accept = $this->apiClient->selectHeaderAccept([]);
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(['application/json']);

        // query params
        if ($subscription_key !== null) {
            $queryParams['subscription-key'] = $this->apiClient->getSerializer()->toQueryValue($subscription_key);
        }
        // header params
        if ($bd_operation_mode !== null) {
            $headerParams['BD-Operation-Mode'] = $this->apiClient->getSerializer()->toHeaderValue($bd_operation_mode);
        }
        // header params
        if ($ocp_apim_subscription_key !== null) {
            $headerParams['Ocp-Apim-Subscription-Key'] = $this->apiClient->getSerializer()->toHeaderValue($ocp_apim_subscription_key);
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        // body params
        $_tempBody = null;
        if (isset($deliveries)) {
            $_tempBody = $deliveries;
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath,
                'POST',
                $queryParams,
                $httpBody,
                $headerParams,
                null,
                '/deliveries'
            );

            return [$response, $statusCode, $httpHeader];
        } catch (ApiException $e) {
            switch ($e->getCode()) {
            }

            throw $e;
        }
    }

    /**
     * Operation deliveriesGet
     *
     * 
     *
     * @param string $deliveries_id The ID of the deliveries that will be retrieved. (required)
     * @param string $subscription_key subscription key in url (optional)
     * @param string $bd_operation_mode Determines if the call is to run in a test development mode or a live production mode. (optional, default to live)
     * @param string $ocp_apim_subscription_key subscription key in header (optional)
     * @throws \Swagger\Client\ApiException on non-2xx response
     * @return void
     */
    public function deliveriesGet($deliveries_id, $subscription_key = null, $bd_operation_mode = null, $ocp_apim_subscription_key = null)
    {
        list($response) = $this->deliveriesGetWithHttpInfo($deliveries_id, $subscription_key, $bd_operation_mode, $ocp_apim_subscription_key);
        return $response;
    }

    /**
     * Operation deliveriesGetWithHttpInfo
     *
     * 
     *
     * @param string $deliveries_id The ID of the deliveries that will be retrieved. (required)
     * @param string $subscription_key subscription key in url (optional)
     * @param string $bd_operation_mode Determines if the call is to run in a test development mode or a live production mode. (optional, default to live)
     * @param string $ocp_apim_subscription_key subscription key in header (optional)
     * @throws \Swagger\Client\ApiException on non-2xx response
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     */
    public function deliveriesGetWithHttpInfo($deliveries_id, $subscription_key = null, $bd_operation_mode = null, $ocp_apim_subscription_key = null)
    {
        // verify the required parameter 'deliveries_id' is set
        if ($deliveries_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $deliveries_id when calling deliveriesGet');
        }
        // parse inputs
        $resourcePath = "/deliveries/{deliveriesId}";
        $httpBody = '';
        $queryParams = [];
        $headerParams = [];
        $formParams = [];
        $_header_accept = $this->apiClient->selectHeaderAccept([]);
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType([]);

        // query params
        if ($subscription_key !== null) {
            $queryParams['subscription-key'] = $this->apiClient->getSerializer()->toQueryValue($subscription_key);
        }
        // header params
        if ($bd_operation_mode !== null) {
            $headerParams['BD-Operation-Mode'] = $this->apiClient->getSerializer()->toHeaderValue($bd_operation_mode);
        }
        // header params
        if ($ocp_apim_subscription_key !== null) {
            $headerParams['Ocp-Apim-Subscription-Key'] = $this->apiClient->getSerializer()->toHeaderValue($ocp_apim_subscription_key);
        }
        // path params
        if ($deliveries_id !== null) {
            $resourcePath = str_replace(
                "{" . "deliveriesId" . "}",
                $this->apiClient->getSerializer()->toPathValue($deliveries_id),
                $resourcePath
            );
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        
        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath,
                'GET',
                $queryParams,
                $httpBody,
                $headerParams,
                null,
                '/deliveries/{deliveriesId}'
            );

            return [null, $statusCode, $httpHeader];
        } catch (ApiException $e) {
            switch ($e->getCode()) {
            }

            throw $e;
        }
    }

    /**
     * Operation deliveriesIndex
     *
     * 
     *
     * @param float $limit Limit the number of records returned. synonym: take (optional, default to 10)
     * @param string $offset Skip the given number of records, used for paging.  synonym: skip (optional)
     * @param string $orderby Order by a particular field either ascending (1) or descending (-1). synonym: sort (optional)
     * @param string $select Specify what fields to return. Example: {\&quot;field1\&quot;: true, \&quot;field2\&quot;: true, \&quot;field3\&quot;: true} synonym: projection (optional)
     * @param string $query Specify the selection criteria for the records returned from the find. Specify a query/find with a mongodb style query, {\&quot;expiryDate\&quot;:{\&quot;$lt\&quot;: \&quot;2017-03-31T02:07:40.525Z\&quot;}} or just specify the fields: {\&quot;field1\&quot;:\&quot;value\&quot;, \&quot;field2\&quot;: true, \&quot;field3\&quot;: 3}. synonym: find (optional)
     * @param string $subscription_key subscription key in url (optional)
     * @param string $ocp_apim_subscription_key subscription key in header (optional)
     * @throws \Swagger\Client\ApiException on non-2xx response
     * @return void
     */
    public function deliveriesIndex($limit = null, $offset = null, $orderby = null, $select = null, $query = null, $subscription_key = null, $ocp_apim_subscription_key = null)
    {
        list($response) = $this->deliveriesIndexWithHttpInfo($limit, $offset, $orderby, $select, $query, $subscription_key, $ocp_apim_subscription_key);
        return $response;
    }

    /**
     * Operation deliveriesIndexWithHttpInfo
     *
     * 
     *
     * @param float $limit Limit the number of records returned. synonym: take (optional, default to 10)
     * @param string $offset Skip the given number of records, used for paging.  synonym: skip (optional)
     * @param string $orderby Order by a particular field either ascending (1) or descending (-1). synonym: sort (optional)
     * @param string $select Specify what fields to return. Example: {\&quot;field1\&quot;: true, \&quot;field2\&quot;: true, \&quot;field3\&quot;: true} synonym: projection (optional)
     * @param string $query Specify the selection criteria for the records returned from the find. Specify a query/find with a mongodb style query, {\&quot;expiryDate\&quot;:{\&quot;$lt\&quot;: \&quot;2017-03-31T02:07:40.525Z\&quot;}} or just specify the fields: {\&quot;field1\&quot;:\&quot;value\&quot;, \&quot;field2\&quot;: true, \&quot;field3\&quot;: 3}. synonym: find (optional)
     * @param string $subscription_key subscription key in url (optional)
     * @param string $ocp_apim_subscription_key subscription key in header (optional)
     * @throws \Swagger\Client\ApiException on non-2xx response
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     */
    public function deliveriesIndexWithHttpInfo($limit = null, $offset = null, $orderby = null, $select = null, $query = null, $subscription_key = null, $ocp_apim_subscription_key = null)
    {
        // parse inputs
        $resourcePath = "/deliveries";
        $httpBody = '';
        $queryParams = [];
        $headerParams = [];
        $formParams = [];
        $_header_accept = $this->apiClient->selectHeaderAccept([]);
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType([]);

        // query params
        if ($limit !== null) {
            $queryParams['limit'] = $this->apiClient->getSerializer()->toQueryValue($limit);
        }
        // query params
        if ($offset !== null) {
            $queryParams['offset'] = $this->apiClient->getSerializer()->toQueryValue($offset);
        }
        // query params
        if ($orderby !== null) {
            $queryParams['orderby'] = $this->apiClient->getSerializer()->toQueryValue($orderby);
        }
        // query params
        if ($select !== null) {
            $queryParams['select'] = $this->apiClient->getSerializer()->toQueryValue($select);
        }
        // query params
        if ($query !== null) {
            $queryParams['query'] = $this->apiClient->getSerializer()->toQueryValue($query);
        }
        // query params
        if ($subscription_key !== null) {
            $queryParams['subscription-key'] = $this->apiClient->getSerializer()->toQueryValue($subscription_key);
        }
        // header params
        if ($ocp_apim_subscription_key !== null) {
            $headerParams['Ocp-Apim-Subscription-Key'] = $this->apiClient->getSerializer()->toHeaderValue($ocp_apim_subscription_key);
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        
        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath,
                'GET',
                $queryParams,
                $httpBody,
                $headerParams,
                null,
                '/deliveries'
            );

            return [null, $statusCode, $httpHeader];
        } catch (ApiException $e) {
            switch ($e->getCode()) {
            }

            throw $e;
        }
    }

    /**
     * Operation estimatesCreate
     *
     * 
     *
     * @param string $subscription_key subscription key in url (optional)
     * @param string $bd_operation_mode Determines if the call is to run in a test development mode or a live production mode. (optional, default to live)
     * @param string $ocp_apim_subscription_key subscription key in header (optional)
     * @param \Swagger\Client\Model\Estimates $estimates Data used to create a new estimates.    Optional Fields:     orderId, userId, purchaseId    Location Parameters (Origin and Destination)  {            \&quot;addressLine1\&quot;: \&quot;String\&quot;,            \&quot;addressLine2\&quot;: \&quot;String\&quot;,            \&quot;city\&quot;: {\&quot;String\&quot;,            \&quot;state\&quot;: \&quot;String\&quot;,            \&quot;postalCode\&quot;: \&quot;String\&quot;,            \&quot;country\&quot;: \&quot;String\&quot;,            \&quot;contact\&quot;: \&quot;String\&quot;,            \&quot;notes\&quot;: \&quot;String\&quot;  }    All area based units are in inches, all weight based units are in pounds. (optional)
     * @throws \Swagger\Client\ApiException on non-2xx response
     * @return void
     */
    public function estimatesCreate($subscription_key = null, $bd_operation_mode = null, $ocp_apim_subscription_key = null, $estimates = null)
    {
        list($response) = $this->estimatesCreateWithHttpInfo($subscription_key, $bd_operation_mode, $ocp_apim_subscription_key, $estimates);
        return $response;
    }

    /**
     * Operation estimatesCreateWithHttpInfo
     *
     * 
     *
     * @param string $subscription_key subscription key in url (optional)
     * @param string $bd_operation_mode Determines if the call is to run in a test development mode or a live production mode. (optional, default to live)
     * @param string $ocp_apim_subscription_key subscription key in header (optional)
     * @param \Swagger\Client\Model\Estimates $estimates Data used to create a new estimates.    Optional Fields:     orderId, userId, purchaseId    Location Parameters (Origin and Destination)  {            \&quot;addressLine1\&quot;: \&quot;String\&quot;,            \&quot;addressLine2\&quot;: \&quot;String\&quot;,            \&quot;city\&quot;: {\&quot;String\&quot;,            \&quot;state\&quot;: \&quot;String\&quot;,            \&quot;postalCode\&quot;: \&quot;String\&quot;,            \&quot;country\&quot;: \&quot;String\&quot;,            \&quot;contact\&quot;: \&quot;String\&quot;,            \&quot;notes\&quot;: \&quot;String\&quot;  }    All area based units are in inches, all weight based units are in pounds. (optional)
     * @throws \Swagger\Client\ApiException on non-2xx response
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     */
    public function estimatesCreateWithHttpInfo($subscription_key = null, $bd_operation_mode = null, $ocp_apim_subscription_key = null, $estimates = null)
    {
        // parse inputs
        $resourcePath = "/estimates";
        $httpBody = '';
        $queryParams = [];
        $headerParams = [];
        $formParams = [];
        $_header_accept = $this->apiClient->selectHeaderAccept([]);
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(['application/json']);

        // query params
        if ($subscription_key !== null) {
            $queryParams['subscription-key'] = $this->apiClient->getSerializer()->toQueryValue($subscription_key);
        }
        // header params
        if ($bd_operation_mode !== null) {
            $headerParams['BD-Operation-Mode'] = $this->apiClient->getSerializer()->toHeaderValue($bd_operation_mode);
        }
        // header params
        if ($ocp_apim_subscription_key !== null) {
            $headerParams['Ocp-Apim-Subscription-Key'] = $this->apiClient->getSerializer()->toHeaderValue($ocp_apim_subscription_key);
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        // body params
        $_tempBody = null;
        if (isset($estimates)) {
            $_tempBody = $estimates;
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath,
                'POST',
                $queryParams,
                $httpBody,
                $headerParams,
                null,
                '/estimates'
            );

            return [$response, $statusCode, $httpHeader];
        } catch (ApiException $e) {
            switch ($e->getCode()) {
            }

            throw $e;
        }
    }

    /**
     * Operation estimatesGet
     *
     * 
     *
     * @param string $estimates_id The ID of the estimates that will be retrieved. (required)
     * @param string $subscription_key subscription key in url (optional)
     * @param string $bd_operation_mode Determines if the call is to run in a test development mode or a live production mode. (optional, default to live)
     * @param string $ocp_apim_subscription_key subscription key in header (optional)
     * @throws \Swagger\Client\ApiException on non-2xx response
     * @return void
     */
    public function estimatesGet($estimates_id, $subscription_key = null, $bd_operation_mode = null, $ocp_apim_subscription_key = null)
    {
        list($response) = $this->estimatesGetWithHttpInfo($estimates_id, $subscription_key, $bd_operation_mode, $ocp_apim_subscription_key);
        return $response;
    }

    /**
     * Operation estimatesGetWithHttpInfo
     *
     * 
     *
     * @param string $estimates_id The ID of the estimates that will be retrieved. (required)
     * @param string $subscription_key subscription key in url (optional)
     * @param string $bd_operation_mode Determines if the call is to run in a test development mode or a live production mode. (optional, default to live)
     * @param string $ocp_apim_subscription_key subscription key in header (optional)
     * @throws \Swagger\Client\ApiException on non-2xx response
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     */
    public function estimatesGetWithHttpInfo($estimates_id, $subscription_key = null, $bd_operation_mode = null, $ocp_apim_subscription_key = null)
    {
        // verify the required parameter 'estimates_id' is set
        if ($estimates_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $estimates_id when calling estimatesGet');
        }
        // parse inputs
        $resourcePath = "/estimates/{estimatesId}";
        $httpBody = '';
        $queryParams = [];
        $headerParams = [];
        $formParams = [];
        $_header_accept = $this->apiClient->selectHeaderAccept([]);
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType([]);

        // query params
        if ($subscription_key !== null) {
            $queryParams['subscription-key'] = $this->apiClient->getSerializer()->toQueryValue($subscription_key);
        }
        // header params
        if ($bd_operation_mode !== null) {
            $headerParams['BD-Operation-Mode'] = $this->apiClient->getSerializer()->toHeaderValue($bd_operation_mode);
        }
        // header params
        if ($ocp_apim_subscription_key !== null) {
            $headerParams['Ocp-Apim-Subscription-Key'] = $this->apiClient->getSerializer()->toHeaderValue($ocp_apim_subscription_key);
        }
        // path params
        if ($estimates_id !== null) {
            $resourcePath = str_replace(
                "{" . "estimatesId" . "}",
                $this->apiClient->getSerializer()->toPathValue($estimates_id),
                $resourcePath
            );
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        
        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath,
                'GET',
                $queryParams,
                $httpBody,
                $headerParams,
                null,
                '/estimates/{estimatesId}'
            );

            return [$response, $statusCode, $httpHeader];
        } catch (ApiException $e) {
            switch ($e->getCode()) {
            }

            throw $e;
        }
    }

    /**
     * Operation estimatesIndex
     *
     * 
     *
     * @param float $limit Limit the number of records returned. synonym: take (optional, default to 10)
     * @param float $offset Skip the given number of records, used for paging.  synonym: skip (optional)
     * @param string $orderby Order by a particular field either ascending (1) or descending (-1). synonym: sort (optional)
     * @param string $select Specify what fields to return. Example: {\&quot;field1\&quot;: true, \&quot;field2\&quot;: true, \&quot;field3\&quot;: true} synonym: projection (optional)
     * @param string $query Specify the selection criteria for the records returned from the find. Specify a query/find with a mongodb style query, {\&quot;expiryDate\&quot;:{\&quot;$lt\&quot;: \&quot;2017-03-31T02:07:40.525Z\&quot;}} or just specify the fields: {\&quot;field1\&quot;:\&quot;value\&quot;, \&quot;field2\&quot;: true, \&quot;field3\&quot;: 3}. synonym: find (optional)
     * @param string $subscription_key subscription key in url (optional)
     * @param string $ocp_apim_subscription_key subscription key in header (optional)
     * @throws \Swagger\Client\ApiException on non-2xx response
     * @return void
     */
    public function estimatesIndex($limit = null, $offset = null, $orderby = null, $select = null, $query = null, $subscription_key = null, $ocp_apim_subscription_key = null)
    {
        list($response) = $this->estimatesIndexWithHttpInfo($limit, $offset, $orderby, $select, $query, $subscription_key, $ocp_apim_subscription_key);
        return $response;
    }

    /**
     * Operation estimatesIndexWithHttpInfo
     *
     * 
     *
     * @param float $limit Limit the number of records returned. synonym: take (optional, default to 10)
     * @param float $offset Skip the given number of records, used for paging.  synonym: skip (optional)
     * @param string $orderby Order by a particular field either ascending (1) or descending (-1). synonym: sort (optional)
     * @param string $select Specify what fields to return. Example: {\&quot;field1\&quot;: true, \&quot;field2\&quot;: true, \&quot;field3\&quot;: true} synonym: projection (optional)
     * @param string $query Specify the selection criteria for the records returned from the find. Specify a query/find with a mongodb style query, {\&quot;expiryDate\&quot;:{\&quot;$lt\&quot;: \&quot;2017-03-31T02:07:40.525Z\&quot;}} or just specify the fields: {\&quot;field1\&quot;:\&quot;value\&quot;, \&quot;field2\&quot;: true, \&quot;field3\&quot;: 3}. synonym: find (optional)
     * @param string $subscription_key subscription key in url (optional)
     * @param string $ocp_apim_subscription_key subscription key in header (optional)
     * @throws \Swagger\Client\ApiException on non-2xx response
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     */
    public function estimatesIndexWithHttpInfo($limit = null, $offset = null, $orderby = null, $select = null, $query = null, $subscription_key = null, $ocp_apim_subscription_key = null)
    {
        // parse inputs
        $resourcePath = "/estimates";
        $httpBody = '';
        $queryParams = [];
        $headerParams = [];
        $formParams = [];
        $_header_accept = $this->apiClient->selectHeaderAccept([]);
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType([]);

        // query params
        if ($limit !== null) {
            $queryParams['limit'] = $this->apiClient->getSerializer()->toQueryValue($limit);
        }
        // query params
        if ($offset !== null) {
            $queryParams['offset'] = $this->apiClient->getSerializer()->toQueryValue($offset);
        }
        // query params
        if ($orderby !== null) {
            $queryParams['orderby'] = $this->apiClient->getSerializer()->toQueryValue($orderby);
        }
        // query params
        if ($select !== null) {
            $queryParams['select'] = $this->apiClient->getSerializer()->toQueryValue($select);
        }
        // query params
        if ($query !== null) {
            $queryParams['query'] = $this->apiClient->getSerializer()->toQueryValue($query);
        }
        // query params
        if ($subscription_key !== null) {
            $queryParams['subscription-key'] = $this->apiClient->getSerializer()->toQueryValue($subscription_key);
        }
        // header params
        if ($ocp_apim_subscription_key !== null) {
            $headerParams['Ocp-Apim-Subscription-Key'] = $this->apiClient->getSerializer()->toHeaderValue($ocp_apim_subscription_key);
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        
        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath,
                'GET',
                $queryParams,
                $httpBody,
                $headerParams,
                null,
                '/estimates'
            );

            return [null, $statusCode, $httpHeader];
        } catch (ApiException $e) {
            switch ($e->getCode()) {
            }

            throw $e;
        }
    }
}
