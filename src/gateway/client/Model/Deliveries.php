<?php
/**
 * Deliveries
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
 * Deliveries Class Doc Comment
 *
 * @category    Class
 * @package     Gateway\Client
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class Deliveries implements ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'deliveries';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = [
        'delivery_id' => 'string',
        'desired_start_date' => '\DateTime',
        'desired_completion_date' => '\DateTime',
        'status' => 'string',
        'notification_emails' => 'string[]',
        'delivery' => '\Gateway\Client\Model\Delivery',
        'caller_id' => 'string',
        'caller_email' => 'string',
        'origin_location' => '\Gateway\Client\Model\OriginLocation',
        'destination_location' => '\Gateway\Client\Model\DestinationLocation',
        'freight_items' => '\Gateway\Client\Model\FreightItems[]',
        'reference_ids' => '\Gateway\Client\Model\ReferenceIds',
        'sandbox' => 'bool',
        '_id' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerFormats = [
        'delivery_id' => null,
        'desired_start_date' => 'date',
        'desired_completion_date' => 'date',
        'status' => null,
        'notification_emails' => null,
        'delivery' => null,
        'caller_id' => null,
        'caller_email' => null,
        'origin_location' => null,
        'destination_location' => null,
        'freight_items' => null,
        'reference_ids' => null,
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
        'delivery_id' => 'deliveryId',
        'desired_start_date' => 'desiredStartDate',
        'desired_completion_date' => 'desiredCompletionDate',
        'status' => 'status',
        'notification_emails' => 'notificationEmails',
        'delivery' => 'delivery',
        'caller_id' => 'callerId',
        'caller_email' => 'callerEmail',
        'origin_location' => 'originLocation',
        'destination_location' => 'destinationLocation',
        'freight_items' => 'freightItems',
        'reference_ids' => 'referenceIds',
        'sandbox' => 'sandbox',
        '_id' => '_id'
    ];


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'delivery_id' => 'setDeliveryId',
        'desired_start_date' => 'setDesiredStartDate',
        'desired_completion_date' => 'setDesiredCompletionDate',
        'status' => 'setStatus',
        'notification_emails' => 'setNotificationEmails',
        'delivery' => 'setDelivery',
        'caller_id' => 'setCallerId',
        'caller_email' => 'setCallerEmail',
        'origin_location' => 'setOriginLocation',
        'destination_location' => 'setDestinationLocation',
        'freight_items' => 'setFreightItems',
        'reference_ids' => 'setReferenceIds',
        'sandbox' => 'setSandbox',
        '_id' => 'setId'
    ];


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'delivery_id' => 'getDeliveryId',
        'desired_start_date' => 'getDesiredStartDate',
        'desired_completion_date' => 'getDesiredCompletionDate',
        'status' => 'getStatus',
        'notification_emails' => 'getNotificationEmails',
        'delivery' => 'getDelivery',
        'caller_id' => 'getCallerId',
        'caller_email' => 'getCallerEmail',
        'origin_location' => 'getOriginLocation',
        'destination_location' => 'getDestinationLocation',
        'freight_items' => 'getFreightItems',
        'reference_ids' => 'getReferenceIds',
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
        $this->container['delivery_id'] = isset($data['delivery_id']) ? $data['delivery_id'] : null;
        $this->container['desired_start_date'] = isset($data['desired_start_date']) ? $data['desired_start_date'] : null;
        $this->container['desired_completion_date'] = isset($data['desired_completion_date']) ? $data['desired_completion_date'] : null;
        $this->container['status'] = isset($data['status']) ? $data['status'] : null;
        $this->container['notification_emails'] = isset($data['notification_emails']) ? $data['notification_emails'] : null;
        $this->container['delivery'] = isset($data['delivery']) ? $data['delivery'] : null;
        $this->container['caller_id'] = isset($data['caller_id']) ? $data['caller_id'] : null;
        $this->container['caller_email'] = isset($data['caller_email']) ? $data['caller_email'] : null;
        $this->container['origin_location'] = isset($data['origin_location']) ? $data['origin_location'] : null;
        $this->container['destination_location'] = isset($data['destination_location']) ? $data['destination_location'] : null;
        $this->container['freight_items'] = isset($data['freight_items']) ? $data['freight_items'] : null;
        $this->container['reference_ids'] = isset($data['reference_ids']) ? $data['reference_ids'] : null;
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
     * Gets delivery_id
     * @return string
     */
    public function getDeliveryId()
    {
        return $this->container['delivery_id'];
    }

    /**
     * Sets delivery_id
     * @param string $delivery_id
     * @return $this
     */
    public function setDeliveryId($delivery_id)
    {
        $this->container['delivery_id'] = $delivery_id;

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
     * Gets notification_emails
     * @return string[]
     */
    public function getNotificationEmails()
    {
        return $this->container['notification_emails'];
    }

    /**
     * Sets notification_emails
     * @param string[] $notification_emails
     * @return $this
     */
    public function setNotificationEmails($notification_emails)
    {
        $this->container['notification_emails'] = $notification_emails;

        return $this;
    }

    /**
     * Gets delivery
     * @return \Gateway\Client\Model\Delivery
     */
    public function getDelivery()
    {
        return $this->container['delivery'];
    }

    /**
     * Sets delivery
     * @param \Gateway\Client\Model\Delivery $delivery
     * @return $this
     */
    public function setDelivery($delivery)
    {
        $this->container['delivery'] = $delivery;

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


