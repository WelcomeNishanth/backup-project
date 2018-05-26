<?php
/**
 * LeadtimesApi
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
 * LeadtimesApi Class Doc Comment
 *
 * @category Class
 * @package  Gateway\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class LeadtimesApi
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
     * @return LeadtimesApi
     */
    public function setApiClient(\Gateway\Client\ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
        return $this;
    }

    /**
     * Operation createleadtimes
     *
     * Create a new leadtimes
     *
     * @param \Gateway\Client\Model\Leadtimes $body Data used to create a new leadtimes (required)
     * @throws \Gateway\Client\ApiException on non-2xx response
     * @return void
     */
    public function createleadtimes($body)
    {
        list($response) = $this->createleadtimesWithHttpInfo($body);
        return $response;
    }

    /**
     * Operation createleadtimesWithHttpInfo
     *
     * Create a new leadtimes
     *
     * @param \Gateway\Client\Model\Leadtimes $body Data used to create a new leadtimes (required)
     * @throws \Gateway\Client\ApiException on non-2xx response
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     */
    public function createleadtimesWithHttpInfo($body)
    {
        // verify the required parameter 'body' is set
        if ($body === null) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling createleadtimes');
        }
        // parse inputs
        $resourcePath = "/leadtimes";
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
                '/leadtimes'
            );

            return [$response, $statusCode, $httpHeader];
        } catch (ApiException $e) {
            switch ($e->getCode()) {
            }

            throw $e;
        }
    }
}