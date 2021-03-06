<?php
/**
 * Shipments
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
 * Shipments Class Doc Comment
 *
 * @category    Class
 * @package     Gateway\Client
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class Shipments implements ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'shipments';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = [
        'shipment_id' => 'string',
        'broker_id' => 'string',
        'broker_name' => 'string',
        'lead_time' => 'int',
        'estimated_total' => 'int',
        'discount_total' => 'int',
        'quote_total' => 'int',
        'start_date' => '\DateTime',
        'completion_date' => '\DateTime',
        'legs' => '\Gateway\Client\Model\Legs[]'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerFormats = [
        'shipment_id' => null,
        'broker_id' => null,
        'broker_name' => null,
        'lead_time' => 'int64',
        'estimated_total' => 'int64',
        'discount_total' => 'int64',
        'quote_total' => 'int64',
        'start_date' => 'date',
        'completion_date' => 'date',
        'legs' => null
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
        'shipment_id' => 'shipmentId',
        'broker_id' => 'brokerId',
        'broker_name' => 'brokerName',
        'lead_time' => 'leadTime',
        'estimated_total' => 'estimatedTotal',
        'discount_total' => 'discountTotal',
        'quote_total' => 'quoteTotal',
        'start_date' => 'startDate',
        'completion_date' => 'completionDate',
        'legs' => 'legs'
    ];


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'shipment_id' => 'setShipmentId',
        'broker_id' => 'setBrokerId',
        'broker_name' => 'setBrokerName',
        'lead_time' => 'setLeadTime',
        'estimated_total' => 'setEstimatedTotal',
        'discount_total' => 'setDiscountTotal',
        'quote_total' => 'setQuoteTotal',
        'start_date' => 'setStartDate',
        'completion_date' => 'setCompletionDate',
        'legs' => 'setLegs'
    ];


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'shipment_id' => 'getShipmentId',
        'broker_id' => 'getBrokerId',
        'broker_name' => 'getBrokerName',
        'lead_time' => 'getLeadTime',
        'estimated_total' => 'getEstimatedTotal',
        'discount_total' => 'getDiscountTotal',
        'quote_total' => 'getQuoteTotal',
        'start_date' => 'getStartDate',
        'completion_date' => 'getCompletionDate',
        'legs' => 'getLegs'
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
        $this->container['shipment_id'] = isset($data['shipment_id']) ? $data['shipment_id'] : null;
        $this->container['broker_id'] = isset($data['broker_id']) ? $data['broker_id'] : null;
        $this->container['broker_name'] = isset($data['broker_name']) ? $data['broker_name'] : null;
        $this->container['lead_time'] = isset($data['lead_time']) ? $data['lead_time'] : null;
        $this->container['estimated_total'] = isset($data['estimated_total']) ? $data['estimated_total'] : null;
        $this->container['discount_total'] = isset($data['discount_total']) ? $data['discount_total'] : null;
        $this->container['quote_total'] = isset($data['quote_total']) ? $data['quote_total'] : null;
        $this->container['start_date'] = isset($data['start_date']) ? $data['start_date'] : null;
        $this->container['completion_date'] = isset($data['completion_date']) ? $data['completion_date'] : null;
        $this->container['legs'] = isset($data['legs']) ? $data['legs'] : null;
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
     * Gets shipment_id
     * @return string
     */
    public function getShipmentId()
    {
        return $this->container['shipment_id'];
    }

    /**
     * Sets shipment_id
     * @param string $shipment_id
     * @return $this
     */
    public function setShipmentId($shipment_id)
    {
        $this->container['shipment_id'] = $shipment_id;

        return $this;
    }

    /**
     * Gets broker_id
     * @return string
     */
    public function getBrokerId()
    {
        return $this->container['broker_id'];
    }

    /**
     * Sets broker_id
     * @param string $broker_id
     * @return $this
     */
    public function setBrokerId($broker_id)
    {
        $this->container['broker_id'] = $broker_id;

        return $this;
    }

    /**
     * Gets broker_name
     * @return string
     */
    public function getBrokerName()
    {
        return $this->container['broker_name'];
    }

    /**
     * Sets broker_name
     * @param string $broker_name
     * @return $this
     */
    public function setBrokerName($broker_name)
    {
        $this->container['broker_name'] = $broker_name;

        return $this;
    }

    /**
     * Gets lead_time
     * @return int
     */
    public function getLeadTime()
    {
        return $this->container['lead_time'];
    }

    /**
     * Sets lead_time
     * @param int $lead_time
     * @return $this
     */
    public function setLeadTime($lead_time)
    {
        $this->container['lead_time'] = $lead_time;

        return $this;
    }

    /**
     * Gets estimated_total
     * @return int
     */
    public function getEstimatedTotal()
    {
        return $this->container['estimated_total'];
    }

    /**
     * Sets estimated_total
     * @param int $estimated_total
     * @return $this
     */
    public function setEstimatedTotal($estimated_total)
    {
        $this->container['estimated_total'] = $estimated_total;

        return $this;
    }

    /**
     * Gets discount_total
     * @return int
     */
    public function getDiscountTotal()
    {
        return $this->container['discount_total'];
    }

    /**
     * Sets discount_total
     * @param int $discount_total
     * @return $this
     */
    public function setDiscountTotal($discount_total)
    {
        $this->container['discount_total'] = $discount_total;

        return $this;
    }

    /**
     * Gets quote_total
     * @return int
     */
    public function getQuoteTotal()
    {
        return $this->container['quote_total'];
    }

    /**
     * Sets quote_total
     * @param int $quote_total
     * @return $this
     */
    public function setQuoteTotal($quote_total)
    {
        $this->container['quote_total'] = $quote_total;

        return $this;
    }

    /**
     * Gets start_date
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->container['start_date'];
    }

    /**
     * Sets start_date
     * @param \DateTime $start_date
     * @return $this
     */
    public function setStartDate($start_date)
    {
        $this->container['start_date'] = $start_date;

        return $this;
    }

    /**
     * Gets completion_date
     * @return \DateTime
     */
    public function getCompletionDate()
    {
        return $this->container['completion_date'];
    }

    /**
     * Sets completion_date
     * @param \DateTime $completion_date
     * @return $this
     */
    public function setCompletionDate($completion_date)
    {
        $this->container['completion_date'] = $completion_date;

        return $this;
    }

    /**
     * Gets legs
     * @return \Gateway\Client\Model\Legs[]
     */
    public function getLegs()
    {
        return $this->container['legs'];
    }

    /**
     * Sets legs
     * @param \Gateway\Client\Model\Legs[] $legs
     * @return $this
     */
    public function setLegs($legs)
    {
        $this->container['legs'] = $legs;

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


