<?php
/**
 * FreightItem
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
 * FreightItem Class Doc Comment
 *
 * @category    Class
 * @package     Invoicing\Client
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class FreightItem implements ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'FreightItem';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = [
        'description' => 'string',
        'class_code' => 'int',
        'nmfc' => 'string',
        'dimensions' => '\Invoicing\Client\Model\Dimensions',
        'weight' => '\Invoicing\Client\Model\Weight',
        'quantity' => 'int',
        'unit' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerFormats = [
        'description' => null,
        'class_code' => null,
        'nmfc' => null,
        'dimensions' => null,
        'weight' => null,
        'quantity' => null,
        'unit' => null
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
        'description' => 'description',
        'class_code' => 'classCode',
        'nmfc' => 'nmfc',
        'dimensions' => 'dimensions',
        'weight' => 'weight',
        'quantity' => 'quantity',
        'unit' => 'unit'
    ];


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'description' => 'setDescription',
        'class_code' => 'setClassCode',
        'nmfc' => 'setNmfc',
        'dimensions' => 'setDimensions',
        'weight' => 'setWeight',
        'quantity' => 'setQuantity',
        'unit' => 'setUnit'
    ];


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'description' => 'getDescription',
        'class_code' => 'getClassCode',
        'nmfc' => 'getNmfc',
        'dimensions' => 'getDimensions',
        'weight' => 'getWeight',
        'quantity' => 'getQuantity',
        'unit' => 'getUnit'
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
        $this->container['description'] = isset($data['description']) ? $data['description'] : null;
        $this->container['class_code'] = isset($data['class_code']) ? $data['class_code'] : null;
        $this->container['nmfc'] = isset($data['nmfc']) ? $data['nmfc'] : null;
        $this->container['dimensions'] = isset($data['dimensions']) ? $data['dimensions'] : null;
        $this->container['weight'] = isset($data['weight']) ? $data['weight'] : null;
        $this->container['quantity'] = isset($data['quantity']) ? $data['quantity'] : null;
        $this->container['unit'] = isset($data['unit']) ? $data['unit'] : null;
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
     * Gets description
     * @return string
     */
    public function getDescription()
    {
        return $this->container['description'];
    }

    /**
     * Sets description
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->container['description'] = $description;

        return $this;
    }

    /**
     * Gets class_code
     * @return int
     */
    public function getClassCode()
    {
        return $this->container['class_code'];
    }

    /**
     * Sets class_code
     * @param int $class_code
     * @return $this
     */
    public function setClassCode($class_code)
    {
        $this->container['class_code'] = $class_code;

        return $this;
    }

    /**
     * Gets nmfc
     * @return string
     */
    public function getNmfc()
    {
        return $this->container['nmfc'];
    }

    /**
     * Sets nmfc
     * @param string $nmfc
     * @return $this
     */
    public function setNmfc($nmfc)
    {
        $this->container['nmfc'] = $nmfc;

        return $this;
    }

    /**
     * Gets dimensions
     * @return \Invoicing\Client\Model\Dimensions
     */
    public function getDimensions()
    {
        return $this->container['dimensions'];
    }

    /**
     * Sets dimensions
     * @param \Invoicing\Client\Model\Dimensions $dimensions
     * @return $this
     */
    public function setDimensions($dimensions)
    {
        $this->container['dimensions'] = $dimensions;

        return $this;
    }

    /**
     * Gets weight
     * @return \Invoicing\Client\Model\Weight
     */
    public function getWeight()
    {
        return $this->container['weight'];
    }

    /**
     * Sets weight
     * @param \Invoicing\Client\Model\Weight $weight
     * @return $this
     */
    public function setWeight($weight)
    {
        $this->container['weight'] = $weight;

        return $this;
    }

    /**
     * Gets quantity
     * @return int
     */
    public function getQuantity()
    {
        return $this->container['quantity'];
    }

    /**
     * Sets quantity
     * @param int $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->container['quantity'] = $quantity;

        return $this;
    }

    /**
     * Gets unit
     * @return string
     */
    public function getUnit()
    {
        return $this->container['unit'];
    }

    /**
     * Sets unit
     * @param string $unit
     * @return $this
     */
    public function setUnit($unit)
    {
        $this->container['unit'] = $unit;

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

