<?php
/**
 * ConsolidationsApi
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
 * ConsolidationsApi Class Doc Comment
 *
 * @category Class
 * @package  Gateway\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class ConsolidationsApi
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
     * @return ConsolidationsApi
     */
    public function setApiClient(\Gateway\Client\ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
        return $this;
    }

    /**
     * Operation createconsolidations
     *
     * Create a new consolidations
     *
     * @param \Gateway\Client\Model\Consolidations $body Data used to create a new consolidations (required)
     * @throws \Gateway\Client\ApiException on non-2xx response
     * @return void
     */
    public function createconsolidations($body)
    {
        list($response) = $this->createconsolidationsWithHttpInfo($body);
        return $response;
    }

    /**
     * Operation createconsolidationsWithHttpInfo
     *
     * Create a new consolidations
     *
     * @param \Gateway\Client\Model\Consolidations $body Data used to create a new consolidations (required)
     * @throws \Gateway\Client\ApiException on non-2xx response
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     */
    public function createconsolidationsWithHttpInfo($body)
    {
        // verify the required parameter 'body' is set
        if ($body === null) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling createconsolidations');
        }
        // parse inputs
        $resourcePath = "/consolidations";
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
                '/consolidations'
            );

            return [$response, $statusCode, $httpHeader];
        } catch (ApiException $e) {
            switch ($e->getCode()) {
            }

            throw $e;
        }
    }

    /**
     * Operation getconsolidations
     *
     * Return a specific consolidations instance.
     *
     * @param string $consolidations_id The ID of the consolidations that will be retrieved. (required)
     * @throws \Gateway\Client\ApiException on non-2xx response
     * @return \Gateway\Client\Model\Consolidations
     */
    public function getconsolidations($consolidations_id)
    {
        list($response) = $this->getconsolidationsWithHttpInfo($consolidations_id);
        return $response;
    }

    /**
     * Operation getconsolidationsWithHttpInfo
     *
     * Return a specific consolidations instance.
     *
     * @param string $consolidations_id The ID of the consolidations that will be retrieved. (required)
     * @throws \Gateway\Client\ApiException on non-2xx response
     * @return array of \Gateway\Client\Model\Consolidations, HTTP status code, HTTP response headers (array of strings)
     */
    public function getconsolidationsWithHttpInfo($consolidations_id)
    {
        // verify the required parameter 'consolidations_id' is set
        if ($consolidations_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $consolidations_id when calling getconsolidations');
        }
        // parse inputs
        $resourcePath = "/consolidations/{consolidationsId}";
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
        if ($consolidations_id !== null) {
            $resourcePath = str_replace(
                "{" . "consolidationsId" . "}",
                $this->apiClient->getSerializer()->toPathValue($consolidations_id),
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
                '\Gateway\Client\Model\Consolidations',
                '/consolidations/{consolidationsId}'
            );

            return [$this->apiClient->getSerializer()->deserialize($response, '\Gateway\Client\Model\Consolidations', $httpHeader), $statusCode, $httpHeader];
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Gateway\Client\Model\Consolidations', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
            }

            throw $e;
        }
    }

    /**
     * Operation getconsolidationss
     *
     * List multiple consolidations resources.
     *
     * @param int $skip How many records to skip when listing. Used for pagination. (optional, default to 0)
     * @param int $limit How many records to limit the output. (optional, default to 10)
     * @param string $sort Which fields to sort the records on. (optional, default to )
     * @param string $select Select which fields will be returned by the query. (optional, default to )
     * @param string $populate Select which fields will be fully populated with the reference. (optional, default to )
     * @throws \Gateway\Client\ApiException on non-2xx response
     * @return \Gateway\Client\Model\ConsolidationsList
     */
    public function getconsolidationss($skip = '0', $limit = '10', $sort = '', $select = '', $populate = '')
    {
        list($response) = $this->getconsolidationssWithHttpInfo($skip, $limit, $sort, $select, $populate);
        return $response;
    }

    /**
     * Operation getconsolidationssWithHttpInfo
     *
     * List multiple consolidations resources.
     *
     * @param int $skip How many records to skip when listing. Used for pagination. (optional, default to 0)
     * @param int $limit How many records to limit the output. (optional, default to 10)
     * @param string $sort Which fields to sort the records on. (optional, default to )
     * @param string $select Select which fields will be returned by the query. (optional, default to )
     * @param string $populate Select which fields will be fully populated with the reference. (optional, default to )
     * @throws \Gateway\Client\ApiException on non-2xx response
     * @return array of \Gateway\Client\Model\ConsolidationsList, HTTP status code, HTTP response headers (array of strings)
     */
    public function getconsolidationssWithHttpInfo($skip = '0', $limit = '10', $sort = '', $select = '', $populate = '')
    {
        // parse inputs
        $resourcePath = "/consolidations";
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
                '\Gateway\Client\Model\ConsolidationsList',
                '/consolidations'
            );

            return [$this->apiClient->getSerializer()->deserialize($response, '\Gateway\Client\Model\ConsolidationsList', $httpHeader), $statusCode, $httpHeader];
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Gateway\Client\Model\ConsolidationsList', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
            }

            throw $e;
        }
    }
}
