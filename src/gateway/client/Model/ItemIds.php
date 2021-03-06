<?php
/**
 * ItemIds
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
 * ItemIds Class Doc Comment
 *
 * @category    Class
 * @package     Gateway\Client
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class ItemIds implements ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'itemIds';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = [
        'freight_item_id' => 'string',
        'cart_item_id' => 'string',
        'order_item_id' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerFormats = [
        'freight_item_id' => null,
        'cart_item_id' => null,
        'order_item_id' => null
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
        'freight_item_id' => 'freightItemId',
        'cart_item_id' => 'cartItemId',
        'order_item_id' => 'orderItemId'
    ];


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'freight_item_id' => 'setFreightItemId',
        'cart_item_id' => 'setCartItemId',
        'order_item_id' => 'setOrderItemId'
    ];


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'freight_item_id' => 'getFreightItemId',
        'cart_item_id' => 'getCartItemId',
        'order_item_id' => 'getOrderItemId'
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
        $this->container['freight_item_id'] = isset($data['freight_item_id']) ? $data['freight_item_id'] : null;
        $this->container['cart_item_id'] = isset($data['cart_item_id']) ? $data['cart_item_id'] : null;
        $this->container['order_item_id'] = isset($data['order_item_id']) ? $data['order_item_id'] : null;
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
     * Gets freight_item_id
     * @return string
     */
    public function getFreightItemId()
    {
        return $this->container['freight_item_id'];
    }

    /**
     * Sets freight_item_id
     * @param string $freight_item_id
     * @return $this
     */
    public function setFreightItemId($freight_item_id)
    {
        $this->container['freight_item_id'] = $freight_item_id;

        return $this;
    }

    /**
     * Gets cart_item_id
     * @return string
     */
    public function getCartItemId()
    {
        return $this->container['cart_item_id'];
    }

    /**
     * Sets cart_item_id
     * @param string $cart_item_id
     * @return $this
     */
    public function setCartItemId($cart_item_id)
    {
        $this->container['cart_item_id'] = $cart_item_id;

        return $this;
    }

    /**
     * Gets order_item_id
     * @return string
     */
    public function getOrderItemId()
    {
        return $this->container['order_item_id'];
    }

    /**
     * Sets order_item_id
     * @param string $order_item_id
     * @return $this
     */
    public function setOrderItemId($order_item_id)
    {
        $this->container['order_item_id'] = $order_item_id;

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


