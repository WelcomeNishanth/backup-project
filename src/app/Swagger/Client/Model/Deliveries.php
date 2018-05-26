<?php
/**
 * Deliveries
 *
 * PHP version 5
 *
 * @category Class
 * @package  Swagger\Client
 * @author   Swaagger Codegen team
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

namespace Swagger\Client\Model;

use \ArrayAccess;

/**
 * Deliveries Class Doc Comment
 *
 * @category    Class
 * @package     Swagger\Client
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
        'estimate_id' => 'string',
        'order_id' => 'string',
        'user_id' => 'string',
        'purchase_ids' => 'string',
        'scheduled_start_date' => '\DateTime',
        'scheduled_completion_date' => '\DateTime',
        'legs' => '\Swagger\Client\Model\Legs[]',
        'shipments' => '\Swagger\Client\Model\Shipments[]',
        'fulfillments' => '\Swagger\Client\Model\Fulfillments[]',
        '_id' => 'string'
    ];

    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = [
        'delivery_id' => 'deliveryId',
        'estimate_id' => 'estimateId',
        'order_id' => 'orderId',
        'user_id' => 'userId',
        'purchase_ids' => 'purchaseIds',
        'scheduled_start_date' => 'scheduledStartDate',
        'scheduled_completion_date' => 'scheduledCompletionDate',
        'legs' => 'legs',
        'shipments' => 'shipments',
        'fulfillments' => 'fulfillments',
        '_id' => '_id'
    ];


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'delivery_id' => 'setDeliveryId',
        'estimate_id' => 'setEstimateId',
        'order_id' => 'setOrderId',
        'user_id' => 'setUserId',
        'purchase_ids' => 'setPurchaseIds',
        'scheduled_start_date' => 'setScheduledStartDate',
        'scheduled_completion_date' => 'setScheduledCompletionDate',
        'legs' => 'setLegs',
        'shipments' => 'setShipments',
        'fulfillments' => 'setFulfillments',
        '_id' => 'setId'
    ];


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'delivery_id' => 'getDeliveryId',
        'estimate_id' => 'getEstimateId',
        'order_id' => 'getOrderId',
        'user_id' => 'getUserId',
        'purchase_ids' => 'getPurchaseIds',
        'scheduled_start_date' => 'getScheduledStartDate',
        'scheduled_completion_date' => 'getScheduledCompletionDate',
        'legs' => 'getLegs',
        'shipments' => 'getShipments',
        'fulfillments' => 'getFulfillments',
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
        $this->container['estimate_id'] = isset($data['estimate_id']) ? $data['estimate_id'] : null;
        $this->container['order_id'] = isset($data['order_id']) ? $data['order_id'] : null;
        $this->container['user_id'] = isset($data['user_id']) ? $data['user_id'] : null;
        $this->container['purchase_ids'] = isset($data['purchase_ids']) ? $data['purchase_ids'] : null;
        $this->container['scheduled_start_date'] = isset($data['scheduled_start_date']) ? $data['scheduled_start_date'] : null;
        $this->container['scheduled_completion_date'] = isset($data['scheduled_completion_date']) ? $data['scheduled_completion_date'] : null;
        $this->container['legs'] = isset($data['legs']) ? $data['legs'] : null;
        $this->container['shipments'] = isset($data['shipments']) ? $data['shipments'] : null;
        $this->container['fulfillments'] = isset($data['fulfillments']) ? $data['fulfillments'] : null;
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
     * Gets estimate_id
     * @return string
     */
    public function getEstimateId()
    {
        return $this->container['estimate_id'];
    }

    /**
     * Sets estimate_id
     * @param string $estimate_id
     * @return $this
     */
    public function setEstimateId($estimate_id)
    {
        $this->container['estimate_id'] = $estimate_id;

        return $this;
    }

    /**
     * Gets order_id
     * @return string
     */
    public function getOrderId()
    {
        return $this->container['order_id'];
    }

    /**
     * Sets order_id
     * @param string $order_id
     * @return $this
     */
    public function setOrderId($order_id)
    {
        $this->container['order_id'] = $order_id;

        return $this;
    }

    /**
     * Gets user_id
     * @return string
     */
    public function getUserId()
    {
        return $this->container['user_id'];
    }

    /**
     * Sets user_id
     * @param string $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->container['user_id'] = $user_id;

        return $this;
    }

    /**
     * Gets purchase_ids
     * @return string
     */
    public function getPurchaseIds()
    {
        return $this->container['purchase_ids'];
    }

    /**
     * Sets purchase_ids
     * @param string $purchase_ids
     * @return $this
     */
    public function setPurchaseIds($purchase_ids)
    {
        $this->container['purchase_ids'] = $purchase_ids;

        return $this;
    }

    /**
     * Gets scheduled_start_date
     * @return \DateTime
     */
    public function getScheduledStartDate()
    {
        return $this->container['scheduled_start_date'];
    }

    /**
     * Sets scheduled_start_date
     * @param \DateTime $scheduled_start_date
     * @return $this
     */
    public function setScheduledStartDate($scheduled_start_date)
    {
        $this->container['scheduled_start_date'] = $scheduled_start_date;

        return $this;
    }

    /**
     * Gets scheduled_completion_date
     * @return \DateTime
     */
    public function getScheduledCompletionDate()
    {
        return $this->container['scheduled_completion_date'];
    }

    /**
     * Sets scheduled_completion_date
     * @param \DateTime $scheduled_completion_date
     * @return $this
     */
    public function setScheduledCompletionDate($scheduled_completion_date)
    {
        $this->container['scheduled_completion_date'] = $scheduled_completion_date;

        return $this;
    }

    /**
     * Gets legs
     * @return \Swagger\Client\Model\Legs[]
     */
    public function getLegs()
    {
        return $this->container['legs'];
    }

    /**
     * Sets legs
     * @param \Swagger\Client\Model\Legs[] $legs
     * @return $this
     */
    public function setLegs($legs)
    {
        $this->container['legs'] = $legs;

        return $this;
    }

    /**
     * Gets shipments
     * @return \Swagger\Client\Model\Shipments[]
     */
    public function getShipments()
    {
        return $this->container['shipments'];
    }

    /**
     * Sets shipments
     * @param \Swagger\Client\Model\Shipments[] $shipments
     * @return $this
     */
    public function setShipments($shipments)
    {
        $this->container['shipments'] = $shipments;

        return $this;
    }

    /**
     * Gets fulfillments
     * @return \Swagger\Client\Model\Fulfillments[]
     */
    public function getFulfillments()
    {
        return $this->container['fulfillments'];
    }

    /**
     * Sets fulfillments
     * @param \Swagger\Client\Model\Fulfillments[] $fulfillments
     * @return $this
     */
    public function setFulfillments($fulfillments)
    {
        $this->container['fulfillments'] = $fulfillments;

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
            return json_encode(\Swagger\Client\ObjectSerializer::sanitizeForSerialization($this), JSON_PRETTY_PRINT);
        }

        return json_encode(\Swagger\Client\ObjectSerializer::sanitizeForSerialization($this));
    }
}

