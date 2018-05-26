<?php
/**
 * Estimates
 *
 * PHP version 5
 *
 * @category Class
 * @package  Gateway\Client
 * @author   Swaagger Codegen team
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

namespace Gateway\Client\Model;

use \ArrayAccess;

/**
 * Estimates Class Doc Comment
 *
 * @category    Class
 * @package     Gateway\Client
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class Estimates implements ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'estimates';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = [
        'methodologies_requested' => 'string[]',
        'origin_location' => '\Gateway\Client\Model\OriginLocation',
        'destination_location' => '\Gateway\Client\Model\DestinationLocation',
        'freight_items' => '\Gateway\Client\Model\FreightItems[]',
        'reference_ids' => '\Gateway\Client\Model\ReferenceIds',
        'intention_handling_value' => 'string[]',
        'intention_handling_notes' => 'string',
        'intention_insurance_value' => 'string[]',
        'intention_insurance_notes' => 'string',
        'accessorials_total' => 'string',
        'desired_start_date' => '\DateTime',
        'desired_completion_date' => '\DateTime',
        'request_date' => '\DateTime',
        'expiry_date' => '\DateTime',
        'status' => 'string',
        'expired' => 'bool',
        'possible_deliveries' => '\Gateway\Client\Model\PossibleDeliveries[]',
        'caller_id' => 'string',
        'caller_email' => 'string',
        'sandbox' => 'bool',
        '_id' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerFormats = [
        'methodologies_requested' => null,
        'origin_location' => null,
        'destination_location' => null,
        'freight_items' => null,
        'reference_ids' => null,
        'intention_handling_value' => null,
        'intention_handling_notes' => null,
        'intention_insurance_value' => null,
        'intention_insurance_notes' => null,
        'accessorials_total' => null,
        'desired_start_date' => 'date',
        'desired_completion_date' => 'date',
        'request_date' => 'date',
        'expiry_date' => 'date',
        'status' => null,
        'expired' => null,
        'possible_deliveries' => null,
        'caller_id' => null,
        'caller_email' => null,
        'sandbox' => null,
        '_id' => null
    ];

    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    public static function swaggerFormats()
    {
        return self::$swaggerFormats;
    }

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = [
        'methodologies_requested' => 'methodologiesRequested',
        'origin_location' => 'originLocation',
        'destination_location' => 'destinationLocation',
        'freight_items' => 'freightItems',
        'reference_ids' => 'referenceIds',
        'intention_handling_value' => 'intention.handling.value',
        'intention_handling_notes' => 'intention.handling.notes',
        'intention_insurance_value' => 'intention.insurance.value',
        'intention_insurance_notes' => 'intention.insurance.notes',
        'accessorials_total' => 'accessorials.total',
        'desired_start_date' => 'desiredStartDate',
        'desired_completion_date' => 'desiredCompletionDate',
        'request_date' => 'requestDate',
        'expiry_date' => 'expiryDate',
        'status' => 'status',
        'expired' => 'expired',
        'possible_deliveries' => 'possibleDeliveries',
        'caller_id' => 'callerId',
        'caller_email' => 'callerEmail',
        'sandbox' => 'sandbox',
        '_id' => '_id'
    ];


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'methodologies_requested' => 'setMethodologiesRequested',
        'origin_location' => 'setOriginLocation',
        'destination_location' => 'setDestinationLocation',
        'freight_items' => 'setFreightItems',
        'reference_ids' => 'setReferenceIds',
        'intention_handling_value' => 'setIntentionHandlingValue',
        'intention_handling_notes' => 'setIntentionHandlingNotes',
        'intention_insurance_value' => 'setIntentionInsuranceValue',
        'intention_insurance_notes' => 'setIntentionInsuranceNotes',
        'accessorials_total' => 'setAccessorialsTotal',
        'desired_start_date' => 'setDesiredStartDate',
        'desired_completion_date' => 'setDesiredCompletionDate',
        'request_date' => 'setRequestDate',
        'expiry_date' => 'setExpiryDate',
        'status' => 'setStatus',
        'expired' => 'setExpired',
        'possible_deliveries' => 'setPossibleDeliveries',
        'caller_id' => 'setCallerId',
        'caller_email' => 'setCallerEmail',
        'sandbox' => 'setSandbox',
        '_id' => 'setId'
    ];


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'methodologies_requested' => 'getMethodologiesRequested',
        'origin_location' => 'getOriginLocation',
        'destination_location' => 'getDestinationLocation',
        'freight_items' => 'getFreightItems',
        'reference_ids' => 'getReferenceIds',
        'intention_handling_value' => 'getIntentionHandlingValue',
        'intention_handling_notes' => 'getIntentionHandlingNotes',
        'intention_insurance_value' => 'getIntentionInsuranceValue',
        'intention_insurance_notes' => 'getIntentionInsuranceNotes',
        'accessorials_total' => 'getAccessorialsTotal',
        'desired_start_date' => 'getDesiredStartDate',
        'desired_completion_date' => 'getDesiredCompletionDate',
        'request_date' => 'getRequestDate',
        'expiry_date' => 'getExpiryDate',
        'status' => 'getStatus',
        'expired' => 'getExpired',
        'possible_deliveries' => 'getPossibleDeliveries',
        'caller_id' => 'getCallerId',
        'caller_email' => 'getCallerEmail',
        'sandbox' => 'getSandbox',
        '_id' => 'getId'
    ];

    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    public static function setters()
    {
        return self::$setters;
    }

    public static function getters()
    {
        return self::$getters;
    }

    

    

    /**
     * Associative array for storing property values
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     * @param mixed[] $data Associated array of property values initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['methodologies_requested'] = isset($data['methodologies_requested']) ? $data['methodologies_requested'] : null;
        $this->container['origin_location'] = isset($data['origin_location']) ? $data['origin_location'] : null;
        $this->container['destination_location'] = isset($data['destination_location']) ? $data['destination_location'] : null;
        $this->container['freight_items'] = isset($data['freight_items']) ? $data['freight_items'] : null;
        $this->container['reference_ids'] = isset($data['reference_ids']) ? $data['reference_ids'] : null;
        $this->container['intention_handling_value'] = isset($data['intention_handling_value']) ? $data['intention_handling_value'] : null;
        $this->container['intention_handling_notes'] = isset($data['intention_handling_notes']) ? $data['intention_handling_notes'] : null;
        $this->container['intention_insurance_value'] = isset($data['intention_insurance_value']) ? $data['intention_insurance_value'] : null;
        $this->container['intention_insurance_notes'] = isset($data['intention_insurance_notes']) ? $data['intention_insurance_notes'] : null;
        $this->container['accessorials_total'] = isset($data['accessorials_total']) ? $data['accessorials_total'] : null;
        $this->container['desired_start_date'] = isset($data['desired_start_date']) ? $data['desired_start_date'] : null;
        $this->container['desired_completion_date'] = isset($data['desired_completion_date']) ? $data['desired_completion_date'] : null;
        $this->container['request_date'] = isset($data['request_date']) ? $data['request_date'] : null;
        $this->container['expiry_date'] = isset($data['expiry_date']) ? $data['expiry_date'] : null;
        $this->container['status'] = isset($data['status']) ? $data['status'] : null;
        $this->container['expired'] = isset($data['expired']) ? $data['expired'] : null;
        $this->container['possible_deliveries'] = isset($data['possible_deliveries']) ? $data['possible_deliveries'] : null;
        $this->container['caller_id'] = isset($data['caller_id']) ? $data['caller_id'] : null;
        $this->container['caller_email'] = isset($data['caller_email']) ? $data['caller_email'] : null;
        $this->container['sandbox'] = isset($data['sandbox']) ? $data['sandbox'] : null;
        $this->container['_id'] = isset($data['_id']) ? $data['_id'] : null;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = [];

        return $invalid_properties;
    }

    /**
     * validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {

        return true;
    }


    /**
     * Gets methodologies_requested
     * @return string[]
     */
    public function getMethodologiesRequested()
    {
        return $this->container['methodologies_requested'];
    }

    /**
     * Sets methodologies_requested
     * @param string[] $methodologies_requested
     * @return $this
     */
    public function setMethodologiesRequested($methodologies_requested)
    {
        $this->container['methodologies_requested'] = $methodologies_requested;

        return $this;
    }

    /**
     * Gets origin_location
     * @return \Gateway\Client\Model\OriginLocation
     */
    public function getOriginLocation()
    {
        return $this->container['origin_location'];
    }

    /**
     * Sets origin_location
     * @param \Gateway\Client\Model\OriginLocation $origin_location
     * @return $this
     */
    public function setOriginLocation($origin_location)
    {
        $this->container['origin_location'] = $origin_location;

        return $this;
    }

    /**
     * Gets destination_location
     * @return \Gateway\Client\Model\DestinationLocation
     */
    public function getDestinationLocation()
    {
        return $this->container['destination_location'];
    }

    /**
     * Sets destination_location
     * @param \Gateway\Client\Model\DestinationLocation $destination_location
     * @return $this
     */
    public function setDestinationLocation($destination_location)
    {
        $this->container['destination_location'] = $destination_location;

        return $this;
    }

    /**
     * Gets freight_items
     * @return \Gateway\Client\Model\FreightItems[]
     */
    public function getFreightItems()
    {
        return $this->container['freight_items'];
    }

    /**
     * Sets freight_items
     * @param \Gateway\Client\Model\FreightItems[] $freight_items
     * @return $this
     */
    public function setFreightItems($freight_items)
    {
        $this->container['freight_items'] = $freight_items;

        return $this;
    }

    /**
     * Gets reference_ids
     * @return \Gateway\Client\Model\ReferenceIds
     */
    public function getReferenceIds()
    {
        return $this->container['reference_ids'];
    }

    /**
     * Sets reference_ids
     * @param \Gateway\Client\Model\ReferenceIds $reference_ids
     * @return $this
     */
    public function setReferenceIds($reference_ids)
    {
        $this->container['reference_ids'] = $reference_ids;

        return $this;
    }

    /**
     * Gets intention_handling_value
     * @return string[]
     */
    public function getIntentionHandlingValue()
    {
        return $this->container['intention_handling_value'];
    }

    /**
     * Sets intention_handling_value
     * @param string[] $intention_handling_value
     * @return $this
     */
    public function setIntentionHandlingValue($intention_handling_value)
    {
        $this->container['intention_handling_value'] = $intention_handling_value;

        return $this;
    }

    /**
     * Gets intention_handling_notes
     * @return string
     */
    public function getIntentionHandlingNotes()
    {
        return $this->container['intention_handling_notes'];
    }

    /**
     * Sets intention_handling_notes
     * @param string $intention_handling_notes
     * @return $this
     */
    public function setIntentionHandlingNotes($intention_handling_notes)
    {
        $this->container['intention_handling_notes'] = $intention_handling_notes;

        return $this;
    }

    /**
     * Gets intention_insurance_value
     * @return string[]
     */
    public function getIntentionInsuranceValue()
    {
        return $this->container['intention_insurance_value'];
    }

    /**
     * Sets intention_insurance_value
     * @param string[] $intention_insurance_value
     * @return $this
     */
    public function setIntentionInsuranceValue($intention_insurance_value)
    {
        $this->container['intention_insurance_value'] = $intention_insurance_value;

        return $this;
    }

    /**
     * Gets intention_insurance_notes
     * @return string
     */
    public function getIntentionInsuranceNotes()
    {
        return $this->container['intention_insurance_notes'];
    }

    /**
     * Sets intention_insurance_notes
     * @param string $intention_insurance_notes
     * @return $this
     */
    public function setIntentionInsuranceNotes($intention_insurance_notes)
    {
        $this->container['intention_insurance_notes'] = $intention_insurance_notes;

        return $this;
    }

    /**
     * Gets accessorials_total
     * @return string
     */
    public function getAccessorialsTotal()
    {
        return $this->container['accessorials_total'];
    }

    /**
     * Sets accessorials_total
     * @param string $accessorials_total
     * @return $this
     */
    public function setAccessorialsTotal($accessorials_total)
    {
        $this->container['accessorials_total'] = $accessorials_total;

        return $this;
    }

    /**
     * Gets desired_start_date
     * @return \DateTime
     */
    public function getDesiredStartDate()
    {
        return $this->container['desired_start_date'];
    }

    /**
     * Sets desired_start_date
     * @param \DateTime $desired_start_date
     * @return $this
     */
    public function setDesiredStartDate($desired_start_date)
    {
        $this->container['desired_start_date'] = $desired_start_date;

        return $this;
    }

    /**
     * Gets desired_completion_date
     * @return \DateTime
     */
    public function getDesiredCompletionDate()
    {
        return $this->container['desired_completion_date'];
    }

    /**
     * Sets desired_completion_date
     * @param \DateTime $desired_completion_date
     * @return $this
     */
    public function setDesiredCompletionDate($desired_completion_date)
    {
        $this->container['desired_completion_date'] = $desired_completion_date;

        return $this;
    }

    /**
     * Gets request_date
     * @return \DateTime
     */
    public function getRequestDate()
    {
        return $this->container['request_date'];
    }

    /**
     * Sets request_date
     * @param \DateTime $request_date
     * @return $this
     */
    public function setRequestDate($request_date)
    {
        $this->container['request_date'] = $request_date;

        return $this;
    }

    /**
     * Gets expiry_date
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->container['expiry_date'];
    }

    /**
     * Sets expiry_date
     * @param \DateTime $expiry_date
     * @return $this
     */
    public function setExpiryDate($expiry_date)
    {
        $this->container['expiry_date'] = $expiry_date;

        return $this;
    }

    /**
     * Gets status
     * @return string
     */
    public function getStatus()
    {
        return $this->container['status'];
    }

    /**
     * Sets status
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->container['status'] = $status;

        return $this;
    }

    /**
     * Gets expired
     * @return bool
     */
    public function getExpired()
    {
        return $this->container['expired'];
    }

    /**
     * Sets expired
     * @param bool $expired
     * @return $this
     */
    public function setExpired($expired)
    {
        $this->container['expired'] = $expired;

        return $this;
    }

    /**
     * Gets possible_deliveries
     * @return \Gateway\Client\Model\PossibleDeliveries[]
     */
    public function getPossibleDeliveries()
    {
        return $this->container['possible_deliveries'];
    }

    /**
     * Sets possible_deliveries
     * @param \Gateway\Client\Model\PossibleDeliveries[] $possible_deliveries
     * @return $this
     */
    public function setPossibleDeliveries($possible_deliveries)
    {
        $this->container['possible_deliveries'] = $possible_deliveries;

        return $this;
    }

    /**
     * Gets caller_id
     * @return string
     */
    public function getCallerId()
    {
        return $this->container['caller_id'];
    }

    /**
     * Sets caller_id
     * @param string $caller_id
     * @return $this
     */
    public function setCallerId($caller_id)
    {
        $this->container['caller_id'] = $caller_id;

        return $this;
    }

    /**
     * Gets caller_email
     * @return string
     */
    public function getCallerEmail()
    {
        return $this->container['caller_email'];
    }

    /**
     * Sets caller_email
     * @param string $caller_email
     * @return $this
     */
    public function setCallerEmail($caller_email)
    {
        $this->container['caller_email'] = $caller_email;

        return $this;
    }

    /**
     * Gets sandbox
     * @return bool
     */
    public function getSandbox()
    {
        return $this->container['sandbox'];
    }

    /**
     * Sets sandbox
     * @param bool $sandbox
     * @return $this
     */
    public function setSandbox($sandbox)
    {
        $this->container['sandbox'] = $sandbox;

        return $this;
    }

    /**
     * Gets _id
     * @return string
     */
    public function getId()
    {
        return $this->container['_id'];
    }

    /**
     * Sets _id
     * @param string $_id ObjectId
     * @return $this
     */
    public function setId($_id)
    {
        $this->container['_id'] = $_id;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     * @param  integer $offset Offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     * @param  integer $offset Offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     * @param  integer $offset Offset
     * @param  mixed   $value  Value to be set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     * @param  integer $offset Offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(\Gateway\Client\ObjectSerializer::sanitizeForSerialization($this), JSON_PRETTY_PRINT);
        }

        return json_encode(\Gateway\Client\ObjectSerializer::sanitizeForSerialization($this));
    }
}

