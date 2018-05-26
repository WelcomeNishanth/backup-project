<?php
/**
 * EstimatesApi
 * PHP version 5
 *
 * @category Class
 * @package  Gateway\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * Delivery Gateway
 *
 * Delivery Gateway
 *
 * OpenAPI spec version: 2.16.2
 * 
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 *
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace Gateway\Client\Api;

use \Gateway\Client\ApiClient;
use \Gateway\Client\ApiException;
use \Gateway\Client\Configuration;
use \Gateway\Client\ObjectSerializer;

/**
 * EstimatesApi Class Doc Comment
 *
 * @category Class
 * @package  Gateway\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class EstimatesApi
{
    /**
     * API Client
     *
     * @var \Gateway\Client\ApiClient instance of the ApiClient
     */
    protected $apiClient;

    /**
     * Constructor
     *
     * @param \Gateway\Client\ApiClient|null $apiClient The api client to use
     */
    public function __construct(\Gateway\Client\ApiClient $apiClient = null)
    {
        if ($apiClient === null) {
            $apiClient = new ApiClient();
        }

        $this->apiClient = $apiClient;
    }

    /**
     * Get API client
     *
     * @return \Gateway\Client\ApiClient get the API client
     */
    public function getApiClient()
    {
        return $this->apiClient;
    }

    /**
     * Set the API client
     *
     * @param \Gateway\Client\ApiClient $apiClient set the API client
     *
     * @return EstimatesApi
     */
    public function setApiClient(\Gateway\Client\ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
        return $this;
    }

    /**
     * Operation createestimates
     *
     * Create a new estimates
     *
     * @param \Gateway\Client\Model\Estimates $body Data used to create a new estimates (required)
     * @throws \Gateway\Client\ApiException on non-2xx response
     * @return void
     */
    public function createestimates($body)
    {
        list($response) = $this->createestimatesWithHttpInfo($body);
        return $response;
    }

    /**
     * Operation createestimatesWithHttpInfo
     *
     * Create a new estimates
     *
     * @param \Gateway\Client\Model\Estimates $body Data used to create a new estimates (required)
     * @throws \Gateway\Client\ApiException on non-2xx response
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     */
    public function createestimatesWithHttpInfo($body)
    {
        // verify the required parameter 'body' is set
        if ($body === null) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling createestimates');
        }
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

        // body params
        $_tempBody = null;
        if (isset($body)) {
            $_tempBody = $body;
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
     * Operation getestimates
     *
     * Return a specific estimates instance.
     *
     * @param string $estimates_id The ID of the estimates that will be retrieved. (required)
     * @throws \Gateway\Client\ApiException on non-2xx response
     * @return \Gateway\Client\Model\Estimates
     */
    public function getestimates($estimates_id)
    {
        list($response) = $this->getestimatesWithHttpInfo($estimates_id);
        return $response;
    }

    /**
     * Operation getestimatesWithHttpInfo
     *
     * Return a specific estimates instance.
     *
     * @param string $estimates_id The ID of the estimates that will be retrieved. (required)
     * @throws \Gateway\Client\ApiException on non-2xx response
     * @return array of \Gateway\Client\Model\Estimates, HTTP status code, HTTP response headers (array of strings)
     */
    public function getestimatesWithHttpInfo($estimates_id)
    {
        // verify the required parameter 'estimates_id' is set
        if ($estimates_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $estimates_id when calling getestimates');
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

        // path params
        if ($estimates_id !== null) {
            $resourcePath = str_replace(
                "{" . "estimatesId" . "}",
                $this->apiClient->getSerializer()->toPathValue($estimates_id),
                $resourcePath
            );
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
                'GET',
                $queryParams,
                $httpBody,
                $headerParams,
                '\Gateway\Client\Model\Estimates',
                '/estimates/{estimatesId}'
            );

            return [$this->apiClient->getSerializer()->deserialize($response, '\Gateway\Client\Model\Estimates', $httpHeader), $statusCode, $httpHeader];
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Gateway\Client\Model\Estimates', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
            }

            throw $e;
        }
    }

    /**
     * Operation getestimatess
     *
     * List multiple estimates resources.
     *
     * @param int $skip How many records to skip when listing. Used for pagination. (optional, default to 0)
     * @param int $limit How many records to limit the output. (optional, default to 10)
     * @param string $sort Which fields to sort the records on. (optional, default to )
     * @param string $select Select which fields will be returned by the query. (optional, default to )
     * @param string $populate Select which fields will be fully populated with the reference. (optional, default to )
     * @param string $query Query by example.                        Pass a JSON object to find a context. For example: {\&quot;context\&quot;: { \&quot;name\&quot;: \&quot;home\&quot;}}. (optional, default to )
     * @param bool $count Set to true to return the number                         of records instead of the documents. (optional, default to false)
     * @throws \Gateway\Client\ApiException on non-2xx response
     * @return \Gateway\Client\Model\EstimatesList
     */
    public function getestimatess($skip = '0', $limit = '10', $sort = '', $select = '', $populate = '', $query = '', $count = 'false')
    {
        list($response) = $this->getestimatessWithHttpInfo($skip, $limit, $sort, $select, $populate, $query, $count);
        return $response;
    }

    /**
     * Operation getestimatessWithHttpInfo
     *
     * List multiple estimates resources.
     *
     * @param int $skip How many records to skip when listing. Used for pagination. (optional, default to 0)
     * @param int $limit How many records to limit the output. (optional, default to 10)
     * @param string $sort Which fields to sort the records on. (optional, default to )
     * @param string $select Select which fields will be returned by the query. (optional, default to )
     * @param string $populate Select which fields will be fully populated with the reference. (optional, default to )
     * @param string $query Query by example.                        Pass a JSON object to find a context. For example: {\&quot;context\&quot;: { \&quot;name\&quot;: \&quot;home\&quot;}}. (optional, default to )
     * @param bool $count Set to true to return the number                         of records instead of the documents. (optional, default to false)
     * @throws \Gateway\Client\ApiException on non-2xx response
     * @return array of \Gateway\Client\Model\EstimatesList, HTTP status code, HTTP response headers (array of strings)
     */
    public function getestimatessWithHttpInfo($skip = '0', $limit = '10', $sort = '', $select = '', $populate = '', $query = '', $count = 'false')
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
        if ($skip !== null) {
            $queryParams['skip'] = $this->apiClient->getSerializer()->toQueryValue($skip);
        }
        // query params
        if ($limit !== null) {
            $queryParams['limit'] = $this->apiClient->getSerializer()->toQueryValue($limit);
        }
        // query params
        if ($sort !== null) {
            $queryParams['sort'] = $this->apiClient->getSerializer()->toQueryValue($sort);
        }
        // query params
        if ($select !== null) {
            $queryParams['select'] = $this->apiClient->getSerializer()->toQueryValue($select);
        }
        // query params
        if ($populate !== null) {
            $queryParams['populate'] = $this->apiClient->getSerializer()->toQueryValue($populate);
        }
        // query params
        if ($query !== null) {
            $queryParams['query'] = $this->apiClient->getSerializer()->toQueryValue($query);
        }
        // query params
        if ($count !== null) {
            $queryParams['count'] = $this->apiClient->getSerializer()->toQueryValue($count);
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
                'GET',
                $queryParams,
                $httpBody,
                $headerParams,
                '\Gateway\Client\Model\EstimatesList',
                '/estimates'
            );

            return [$this->apiClient->getSerializer()->deserialize($response, '\Gateway\Client\Model\EstimatesList', $httpHeader), $statusCode, $httpHeader];
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Gateway\Client\Model\EstimatesList', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
            }

            throw $e;
        }
    }

    /**
     * Operation updateestimates
     *
     * Update a specific estimates instance.
     *
     * @param string $estimates_id The ID of the estimates that will be updated. (required)
     * @param \Gateway\Client\Model\Estimates $body Data used to update estimates (required)
     * @throws \Gateway\Client\ApiException on non-2xx response
     * @return \Gateway\Client\Model\Estimates
     */
    public function updateestimates($estimates_id, $body)
    {
        list($response) = $this->updateestimatesWithHttpInfo($estimates_id, $body);
        return $response;
    }

    /**
     * Operation updateestimatesWithHttpInfo
     *
     * Update a specific estimates instance.
     *
     * @param string $estimates_id The ID of the estimates that will be updated. (required)
     * @param \Gateway\Client\Model\Estimates $body Data used to update estimates (required)
     * @throws \Gateway\Client\ApiException on non-2xx response
     * @return array of \Gateway\Client\Model\Estimates, HTTP status code, HTTP response headers (array of strings)
     */
    public function updateestimatesWithHttpInfo($estimates_id, $body)
    {
        // verify the required parameter 'estimates_id' is set
        if ($estimates_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $estimates_id when calling updateestimates');
        }
        // verify the required parameter 'body' is set
        if ($body === null) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateestimates');
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

        // path params
        if ($estimates_id !== null) {
            $resourcePath = str_replace(
                "{" . "estimatesId" . "}",
                $this->apiClient->getSerializer()->toPathValue($estimates_id),
                $resourcePath
            );
        }
        // body params
        $_tempBody = null;
        if (isset($body)) {
            $_tempBody = $body;
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
                'PUT',
                $queryParams,
                $httpBody,
                $headerParams,
                '\Gateway\Client\Model\Estimates',
                '/estimates/{estimatesId}'
            );

            return [$this->apiClient->getSerializer()->deserialize($response, '\Gateway\Client\Model\Estimates', $httpHeader), $statusCode, $httpHeader];
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Gateway\Client\Model\Estimates', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
            }

            throw $e;
        }
    }
}
