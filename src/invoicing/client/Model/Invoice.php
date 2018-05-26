<?php
/**
 * Invoice
 *
 * PHP version 5
 *
 * @category Class
 * @package  Invoicing\Client
 * @author   Swaagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * Gateway Invoicing APIs
 *
 * With the Invoicing APIs, you can create, get and update Customers and Invoices in Netsuite through REST APIs. This is a wrapper around NetSuite SOAP APIs and built for Gateway Invoicing requirements.
 *
 * OpenAPI spec version: 1.0
 * Contact: apiteam@builddirect.com
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 *
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace Invoicing\Client\Model;

use \ArrayAccess;

/**
 * Invoice Class Doc Comment
 *
 * @category    Class
 * @package     Invoicing\Client
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class Invoice implements ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'Invoice';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = [
        'delivery_id' => 'string',
        'expected_delivery_date' => 'string',
        'actual_delivery_date' => 'string',
        'reference_ids' => 'string[]',
        'costs' => '\Invoicing\Client\Model\Costs',
        'sender_address' => '\Invoicing\Client\Model\ContactAddress',
        'destination_address' => '\Invoicing\Client\Model\ContactAddress',
        'freight_items' => '\Invoicing\Client\Model\FreightItem[]'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerFormats = [
        'delivery_id' => null,
        'expected_delivery_date' => null,
        'actual_delivery_date' => null,
        'reference_ids' => null,
        'costs' => null,
        'sender_address' => null,
        'destination_address' => null,
        'freight_items' => null
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
        'delivery_id' => 'delivery_id',
        'expected_delivery_date' => 'expected_delivery_date',
        'actual_delivery_date' => 'actual_delivery_date',
        'reference_ids' => 'reference_ids',
        'costs' => 'costs',
        'sender_address' => 'sender_address',
        'destination_address' => 'destination_address',
        'freight_items' => 'freight_items'
    ];


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'delivery_id' => 'setDeliveryId',
        'expected_delivery_date' => 'setExpectedDeliveryDate',
        'actual_delivery_date' => 'setActualDeliveryDate',
        'reference_ids' => 'setReferenceIds',
        'costs' => 'setCosts',
        'sender_address' => 'setSenderAddress',
        'destination_address' => 'setDestinationAddress',
        'freight_items' => 'setFreightItems'
    ];


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'delivery_id' => 'getDeliveryId',
        'expected_delivery_date' => 'getExpectedDeliveryDate',
        'actual_delivery_date' => 'getActualDeliveryDate',
        'reference_ids' => 'getReferenceIds',
        'costs' => 'getCosts',
        'sender_address' => 'getSenderAddress',
        'destination_address' => 'getDestinationAddress',
        'freight_items' => 'getFreightItems'
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
        $this->container['expected_delivery_date'] = isset($data['expected_delivery_date']) ? $data['expected_delivery_date'] : null;
        $this->container['actual_delivery_date'] = isset($data['actual_delivery_date']) ? $data['actual_delivery_date'] : null;
        $this->container['reference_ids'] = isset($data['reference_ids']) ? $data['reference_ids'] : null;
        $this->container['costs'] = isset($data['costs']) ? $data['costs'] : null;
        $this->container['sender_address'] = isset($data['sender_address']) ? $data['sender_address'] : null;
        $this->container['destination_address'] = isset($data['destination_address']) ? $data['destination_address'] : null;
        $this->container['freight_items'] = isset($data['freight_items']) ? $data['freight_items'] : null;
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
     * Gets expected_delivery_date
     * @return string
     */
    public function getExpectedDeliveryDate()
    {
        return $this->container['expected_delivery_date'];
    }

    /**
     * Sets expected_delivery_date
     * @param string $expected_delivery_date
     * @return $this
     */
    public function setExpectedDeliveryDate($expected_delivery_date)
    {
        $this->container['expected_delivery_date'] = $expected_delivery_date;

        return $this;
    }

    /**
     * Gets actual_delivery_date
     * @return string
     */
    public function getActualDeliveryDate()
    {
        return $this->container['actual_delivery_date'];
    }

    /**
     * Sets actual_delivery_date
     * @param string $actual_delivery_date
     * @return $this
     */
    public function setActualDeliveryDate($actual_delivery_date)
    {
        $this->container['actual_delivery_date'] = $actual_delivery_date;

        return $this;
    }

    /**
     * Gets reference_ids
     * @return string[]
     */
    public function getReferenceIds()
    {
        return $this->container['reference_ids'];
    }

    /**
     * Sets reference_ids
     * @param string[] $reference_ids
     * @return $this
     */
    public function setReferenceIds($reference_ids)
    {
        $this->container['reference_ids'] = $reference_ids;

        return $this;
    }

    /**
     * Gets costs
     * @return \Invoicing\Client\Model\Costs
     */
    public function getCosts()
    {
        return $this->container['costs'];
    }

    /**
     * Sets costs
     * @param \Invoicing\Client\Model\Costs $costs
     * @return $this
     */
    public function setCosts($costs)
    {
        $this->container['costs'] = $costs;

        return $this;
    }

    /**
     * Gets sender_address
     * @return \Invoicing\Client\Model\ContactAddress
     */
    public function getSenderAddress()
    {
        return $this->container['sender_address'];
    }

    /**
     * Sets sender_address
     * @param \Invoicing\Client\Model\ContactAddress $sender_address
     * @return $this
     */
    public function setSenderAddress($sender_address)
    {
        $this->container['sender_address'] = $sender_address;

        return $this;
    }

    /**
     * Gets destination_address
     * @return \Invoicing\Client\Model\ContactAddress
     */
    public function getDestinationAddress()
    {
        return $this->container['destination_address'];
    }

    /**
     * Sets destination_address
     * @param \Invoicing\Client\Model\ContactAddress $destination_address
     * @return $this
     */
    public function setDestinationAddress($destination_address)
    {
        $this->container['destination_address'] = $destination_address;

        return $this;
    }

    /**
     * Gets freight_items
     * @return \Invoicing\Client\Model\FreightItem[]
     */
    public function getFreightItems()
    {
        return $this->container['freight_items'];
    }

    /**
     * Sets freight_items
     * @param \Invoicing\Client\Model\FreightItem[] $freight_items
     * @return $this
     */
    public function setFreightItems($freight_items)
    {
        $this->container['freight_items'] = $freight_items;

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
            return json_encode(\Invoicing\Client\ObjectSerializer::sanitizeForSerialization($this), JSON_PRETTY_PRINT);
        }

        return json_encode(\Invoicing\Client\ObjectSerializer::sanitizeForSerialization($this));
    }
}


