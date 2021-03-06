<?php
/**
 * Legs
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
 * Legs Class Doc Comment
 *
 * @category    Class
 * @package     Gateway\Client
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class Legs implements ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'legs';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = [
        'leg_id' => 'string',
        'next_index' => 'int',
        'transit_duration_minutes' => 'int',
        'buffer_duration_minutes' => 'int',
        'departure_cron' => 'string',
        'mechanism' => 'string',
        'carrier_id' => 'string',
        'carrier_name' => 'string',
        'pick_time' => 'int',
        'leaving_day' => 'string',
        'transit_time' => 'int',
        'lead_time' => 'int',
        'time_sensitive' => 'bool',
        'estimate' => 'int',
        'discount' => 'int',
        'quote' => 'int',
        'departure_date' => '\DateTime',
        'arrival_date' => '\DateTime',
        'dropoff_location' => '\Gateway\Client\Model\DropoffLocation',
        'terminal_location' => '\Gateway\Client\Model\TerminalLocation',
        'origin_location' => '\Gateway\Client\Model\OriginLocation',
        'rated_origin_location' => '\Gateway\Client\Model\RatedOriginLocation',
        'destination_location' => '\Gateway\Client\Model\DestinationLocation',
        'freight_items' => '\Gateway\Client\Model\FreightItems[]',
        'packaged_items' => '\Gateway\Client\Model\PackagedItems[]',
        'pallet_count' => 'int',
        'documents' => '\Gateway\Client\Model\Documents[]',
        'intention_handling_value' => 'string[]',
        'intention_handling_notes' => 'string',
        'plan_handling_value' => 'string[]',
        'plan_handling_notes' => 'string',
        'shipment_rate_type' => 'string',
        'shipment_load' => 'string',
        'quote_id' => 'string',
        'quote_source' => 'string',
        'tender_id' => 'string',
        'affiliated_tender_ids' => 'string[]'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerFormats = [
        'leg_id' => null,
        'next_index' => 'int64',
        'transit_duration_minutes' => 'int64',
        'buffer_duration_minutes' => 'int64',
        'departure_cron' => null,
        'mechanism' => null,
        'carrier_id' => null,
        'carrier_name' => null,
        'pick_time' => 'int64',
        'leaving_day' => null,
        'transit_time' => 'int64',
        'lead_time' => 'int64',
        'time_sensitive' => null,
        'estimate' => 'int64',
        'discount' => 'int64',
        'quote' => 'int64',
        'departure_date' => 'date',
        'arrival_date' => 'date',
        'dropoff_location' => null,
        'terminal_location' => null,
        'origin_location' => null,
        'rated_origin_location' => null,
        'destination_location' => null,
        'freight_items' => null,
        'packaged_items' => null,
        'pallet_count' => 'int64',
        'documents' => null,
        'intention_handling_value' => null,
        'intention_handling_notes' => null,
        'plan_handling_value' => null,
        'plan_handling_notes' => null,
        'shipment_rate_type' => null,
        'shipment_load' => null,
        'quote_id' => null,
        'quote_source' => null,
        'tender_id' => null,
        'affiliated_tender_ids' => null
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
        'leg_id' => 'legId',
        'next_index' => 'nextIndex',
        'transit_duration_minutes' => 'transitDurationMinutes',
        'buffer_duration_minutes' => 'bufferDurationMinutes',
        'departure_cron' => 'departureCron',
        'mechanism' => 'mechanism',
        'carrier_id' => 'carrierId',
        'carrier_name' => 'carrierName',
        'pick_time' => 'pickTime',
        'leaving_day' => 'leavingDay',
        'transit_time' => 'transitTime',
        'lead_time' => 'leadTime',
        'time_sensitive' => 'timeSensitive',
        'estimate' => 'estimate',
        'discount' => 'discount',
        'quote' => 'quote',
        'departure_date' => 'departureDate',
        'arrival_date' => 'arrivalDate',
        'dropoff_location' => 'dropoffLocation',
        'terminal_location' => 'terminalLocation',
        'origin_location' => 'originLocation',
        'rated_origin_location' => 'ratedOriginLocation',
        'destination_location' => 'destinationLocation',
        'freight_items' => 'freightItems',
        'packaged_items' => 'packagedItems',
        'pallet_count' => 'palletCount',
        'documents' => 'documents',
        'intention_handling_value' => 'intention.handling.value',
        'intention_handling_notes' => 'intention.handling.notes',
        'plan_handling_value' => 'plan.handling.value',
        'plan_handling_notes' => 'plan.handling.notes',
        'shipment_rate_type' => 'shipmentRateType',
        'shipment_load' => 'shipmentLoad',
        'quote_id' => 'quoteId',
        'quote_source' => 'quoteSource',
        'tender_id' => 'tenderId',
        'affiliated_tender_ids' => 'affiliatedTenderIds'
    ];


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'leg_id' => 'setLegId',
        'next_index' => 'setNextIndex',
        'transit_duration_minutes' => 'setTransitDurationMinutes',
        'buffer_duration_minutes' => 'setBufferDurationMinutes',
        'departure_cron' => 'setDepartureCron',
        'mechanism' => 'setMechanism',
        'carrier_id' => 'setCarrierId',
        'carrier_name' => 'setCarrierName',
        'pick_time' => 'setPickTime',
        'leaving_day' => 'setLeavingDay',
        'transit_time' => 'setTransitTime',
        'lead_time' => 'setLeadTime',
        'time_sensitive' => 'setTimeSensitive',
        'estimate' => 'setEstimate',
        'discount' => 'setDiscount',
        'quote' => 'setQuote',
        'departure_date' => 'setDepartureDate',
        'arrival_date' => 'setArrivalDate',
        'dropoff_location' => 'setDropoffLocation',
        'terminal_location' => 'setTerminalLocation',
        'origin_location' => 'setOriginLocation',
        'rated_origin_location' => 'setRatedOriginLocation',
        'destination_location' => 'setDestinationLocation',
        'freight_items' => 'setFreightItems',
        'packaged_items' => 'setPackagedItems',
        'pallet_count' => 'setPalletCount',
        'documents' => 'setDocuments',
        'intention_handling_value' => 'setIntentionHandlingValue',
        'intention_handling_notes' => 'setIntentionHandlingNotes',
        'plan_handling_value' => 'setPlanHandlingValue',
        'plan_handling_notes' => 'setPlanHandlingNotes',
        'shipment_rate_type' => 'setShipmentRateType',
        'shipment_load' => 'setShipmentLoad',
        'quote_id' => 'setQuoteId',
        'quote_source' => 'setQuoteSource',
        'tender_id' => 'setTenderId',
        'affiliated_tender_ids' => 'setAffiliatedTenderIds'
    ];


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'leg_id' => 'getLegId',
        'next_index' => 'getNextIndex',
        'transit_duration_minutes' => 'getTransitDurationMinutes',
        'buffer_duration_minutes' => 'getBufferDurationMinutes',
        'departure_cron' => 'getDepartureCron',
        'mechanism' => 'getMechanism',
        'carrier_id' => 'getCarrierId',
        'carrier_name' => 'getCarrierName',
        'pick_time' => 'getPickTime',
        'leaving_day' => 'getLeavingDay',
        'transit_time' => 'getTransitTime',
        'lead_time' => 'getLeadTime',
        'time_sensitive' => 'getTimeSensitive',
        'estimate' => 'getEstimate',
        'discount' => 'getDiscount',
        'quote' => 'getQuote',
        'departure_date' => 'getDepartureDate',
        'arrival_date' => 'getArrivalDate',
        'dropoff_location' => 'getDropoffLocation',
        'terminal_location' => 'getTerminalLocation',
        'origin_location' => 'getOriginLocation',
        'rated_origin_location' => 'getRatedOriginLocation',
        'destination_location' => 'getDestinationLocation',
        'freight_items' => 'getFreightItems',
        'packaged_items' => 'getPackagedItems',
        'pallet_count' => 'getPalletCount',
        'documents' => 'getDocuments',
        'intention_handling_value' => 'getIntentionHandlingValue',
        'intention_handling_notes' => 'getIntentionHandlingNotes',
        'plan_handling_value' => 'getPlanHandlingValue',
        'plan_handling_notes' => 'getPlanHandlingNotes',
        'shipment_rate_type' => 'getShipmentRateType',
        'shipment_load' => 'getShipmentLoad',
        'quote_id' => 'getQuoteId',
        'quote_source' => 'getQuoteSource',
        'tender_id' => 'getTenderId',
        'affiliated_tender_ids' => 'getAffiliatedTenderIds'
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
        $this->container['leg_id'] = isset($data['leg_id']) ? $data['leg_id'] : null;
        $this->container['next_index'] = isset($data['next_index']) ? $data['next_index'] : null;
        $this->container['transit_duration_minutes'] = isset($data['transit_duration_minutes']) ? $data['transit_duration_minutes'] : null;
        $this->container['buffer_duration_minutes'] = isset($data['buffer_duration_minutes']) ? $data['buffer_duration_minutes'] : null;
        $this->container['departure_cron'] = isset($data['departure_cron']) ? $data['departure_cron'] : null;
        $this->container['mechanism'] = isset($data['mechanism']) ? $data['mechanism'] : null;
        $this->container['carrier_id'] = isset($data['carrier_id']) ? $data['carrier_id'] : null;
        $this->container['carrier_name'] = isset($data['carrier_name']) ? $data['carrier_name'] : null;
        $this->container['pick_time'] = isset($data['pick_time']) ? $data['pick_time'] : null;
        $this->container['leaving_day'] = isset($data['leaving_day']) ? $data['leaving_day'] : null;
        $this->container['transit_time'] = isset($data['transit_time']) ? $data['transit_time'] : null;
        $this->container['lead_time'] = isset($data['lead_time']) ? $data['lead_time'] : null;
        $this->container['time_sensitive'] = isset($data['time_sensitive']) ? $data['time_sensitive'] : null;
        $this->container['estimate'] = isset($data['estimate']) ? $data['estimate'] : null;
        $this->container['discount'] = isset($data['discount']) ? $data['discount'] : null;
        $this->container['quote'] = isset($data['quote']) ? $data['quote'] : null;
        $this->container['departure_date'] = isset($data['departure_date']) ? $data['departure_date'] : null;
        $this->container['arrival_date'] = isset($data['arrival_date']) ? $data['arrival_date'] : null;
        $this->container['dropoff_location'] = isset($data['dropoff_location']) ? $data['dropoff_location'] : null;
        $this->container['terminal_location'] = isset($data['terminal_location']) ? $data['terminal_location'] : null;
        $this->container['origin_location'] = isset($data['origin_location']) ? $data['origin_location'] : null;
        $this->container['rated_origin_location'] = isset($data['rated_origin_location']) ? $data['rated_origin_location'] : null;
        $this->container['destination_location'] = isset($data['destination_location']) ? $data['destination_location'] : null;
        $this->container['freight_items'] = isset($data['freight_items']) ? $data['freight_items'] : null;
        $this->container['packaged_items'] = isset($data['packaged_items']) ? $data['packaged_items'] : null;
        $this->container['pallet_count'] = isset($data['pallet_count']) ? $data['pallet_count'] : null;
        $this->container['documents'] = isset($data['documents']) ? $data['documents'] : null;
        $this->container['intention_handling_value'] = isset($data['intention_handling_value']) ? $data['intention_handling_value'] : null;
        $this->container['intention_handling_notes'] = isset($data['intention_handling_notes']) ? $data['intention_handling_notes'] : null;
        $this->container['plan_handling_value'] = isset($data['plan_handling_value']) ? $data['plan_handling_value'] : null;
        $this->container['plan_handling_notes'] = isset($data['plan_handling_notes']) ? $data['plan_handling_notes'] : null;
        $this->container['shipment_rate_type'] = isset($data['shipment_rate_type']) ? $data['shipment_rate_type'] : null;
        $this->container['shipment_load'] = isset($data['shipment_load']) ? $data['shipment_load'] : null;
        $this->container['quote_id'] = isset($data['quote_id']) ? $data['quote_id'] : null;
        $this->container['quote_source'] = isset($data['quote_source']) ? $data['quote_source'] : null;
        $this->container['tender_id'] = isset($data['tender_id']) ? $data['tender_id'] : null;
        $this->container['affiliated_tender_ids'] = isset($data['affiliated_tender_ids']) ? $data['affiliated_tender_ids'] : null;
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
     * Gets leg_id
     * @return string
     */
    public function getLegId()
    {
        return $this->container['leg_id'];
    }

    /**
     * Sets leg_id
     * @param string $leg_id
     * @return $this
     */
    public function setLegId($leg_id)
    {
        $this->container['leg_id'] = $leg_id;

        return $this;
    }

    /**
     * Gets next_index
     * @return int
     */
    public function getNextIndex()
    {
        return $this->container['next_index'];
    }

    /**
     * Sets next_index
     * @param int $next_index
     * @return $this
     */
    public function setNextIndex($next_index)
    {
        $this->container['next_index'] = $next_index;

        return $this;
    }

    /**
     * Gets transit_duration_minutes
     * @return int
     */
    public function getTransitDurationMinutes()
    {
        return $this->container['transit_duration_minutes'];
    }

    /**
     * Sets transit_duration_minutes
     * @param int $transit_duration_minutes
     * @return $this
     */
    public function setTransitDurationMinutes($transit_duration_minutes)
    {
        $this->container['transit_duration_minutes'] = $transit_duration_minutes;

        return $this;
    }

    /**
     * Gets buffer_duration_minutes
     * @return int
     */
    public function getBufferDurationMinutes()
    {
        return $this->container['buffer_duration_minutes'];
    }

    /**
     * Sets buffer_duration_minutes
     * @param int $buffer_duration_minutes
     * @return $this
     */
    public function setBufferDurationMinutes($buffer_duration_minutes)
    {
        $this->container['buffer_duration_minutes'] = $buffer_duration_minutes;

        return $this;
    }

    /**
     * Gets departure_cron
     * @return string
     */
    public function getDepartureCron()
    {
        return $this->container['departure_cron'];
    }

    /**
     * Sets departure_cron
     * @param string $departure_cron
     * @return $this
     */
    public function setDepartureCron($departure_cron)
    {
        $this->container['departure_cron'] = $departure_cron;

        return $this;
    }

    /**
     * Gets mechanism
     * @return string
     */
    public function getMechanism()
    {
        return $this->container['mechanism'];
    }

    /**
     * Sets mechanism
     * @param string $mechanism
     * @return $this
     */
    public function setMechanism($mechanism)
    {
        $this->container['mechanism'] = $mechanism;

        return $this;
    }

    /**
     * Gets carrier_id
     * @return string
     */
    public function getCarrierId()
    {
        return $this->container['carrier_id'];
    }

    /**
     * Sets carrier_id
     * @param string $carrier_id
     * @return $this
     */
    public function setCarrierId($carrier_id)
    {
        $this->container['carrier_id'] = $carrier_id;

        return $this;
    }

    /**
     * Gets carrier_name
     * @return string
     */
    public function getCarrierName()
    {
        return $this->container['carrier_name'];
    }

    /**
     * Sets carrier_name
     * @param string $carrier_name
     * @return $this
     */
    public function setCarrierName($carrier_name)
    {
        $this->container['carrier_name'] = $carrier_name;

        return $this;
    }

    /**
     * Gets pick_time
     * @return int
     */
    public function getPickTime()
    {
        return $this->container['pick_time'];
    }

    /**
     * Sets pick_time
     * @param int $pick_time
     * @return $this
     */
    public function setPickTime($pick_time)
    {
        $this->container['pick_time'] = $pick_time;

        return $this;
    }

    /**
     * Gets leaving_day
     * @return string
     */
    public function getLeavingDay()
    {
        return $this->container['leaving_day'];
    }

    /**
     * Sets leaving_day
     * @param string $leaving_day
     * @return $this
     */
    public function setLeavingDay($leaving_day)
    {
        $this->container['leaving_day'] = $leaving_day;

        return $this;
    }

    /**
     * Gets transit_time
     * @return int
     */
    public function getTransitTime()
    {
        return $this->container['transit_time'];
    }

    /**
     * Sets transit_time
     * @param int $transit_time
     * @return $this
     */
    public function setTransitTime($transit_time)
    {
        $this->container['transit_time'] = $transit_time;

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
     * Gets time_sensitive
     * @return bool
     */
    public function getTimeSensitive()
    {
        return $this->container['time_sensitive'];
    }

    /**
     * Sets time_sensitive
     * @param bool $time_sensitive
     * @return $this
     */
    public function setTimeSensitive($time_sensitive)
    {
        $this->container['time_sensitive'] = $time_sensitive;

        return $this;
    }

    /**
     * Gets estimate
     * @return int
     */
    public function getEstimate()
    {
        return $this->container['estimate'];
    }

    /**
     * Sets estimate
     * @param int $estimate
     * @return $this
     */
    public function setEstimate($estimate)
    {
        $this->container['estimate'] = $estimate;

        return $this;
    }

    /**
     * Gets discount
     * @return int
     */
    public function getDiscount()
    {
        return $this->container['discount'];
    }

    /**
     * Sets discount
     * @param int $discount
     * @return $this
     */
    public function setDiscount($discount)
    {
        $this->container['discount'] = $discount;

        return $this;
    }

    /**
     * Gets quote
     * @return int
     */
    public function getQuote()
    {
        return $this->container['quote'];
    }

    /**
     * Sets quote
     * @param int $quote
     * @return $this
     */
    public function setQuote($quote)
    {
        $this->container['quote'] = $quote;

        return $this;
    }

    /**
     * Gets departure_date
     * @return \DateTime
     */
    public function getDepartureDate()
    {
        return $this->container['departure_date'];
    }

    /**
     * Sets departure_date
     * @param \DateTime $departure_date
     * @return $this
     */
    public function setDepartureDate($departure_date)
    {
        $this->container['departure_date'] = $departure_date;

        return $this;
    }

    /**
     * Gets arrival_date
     * @return \DateTime
     */
    public function getArrivalDate()
    {
        return $this->container['arrival_date'];
    }

    /**
     * Sets arrival_date
     * @param \DateTime $arrival_date
     * @return $this
     */
    public function setArrivalDate($arrival_date)
    {
        $this->container['arrival_date'] = $arrival_date;

        return $this;
    }

    /**
     * Gets dropoff_location
     * @return \Gateway\Client\Model\DropoffLocation
     */
    public function getDropoffLocation()
    {
        return $this->container['dropoff_location'];
    }

    /**
     * Sets dropoff_location
     * @param \Gateway\Client\Model\DropoffLocation $dropoff_location
     * @return $this
     */
    public function setDropoffLocation($dropoff_location)
    {
        $this->container['dropoff_location'] = $dropoff_location;

        return $this;
    }

    /**
     * Gets terminal_location
     * @return \Gateway\Client\Model\TerminalLocation
     */
    public function getTerminalLocation()
    {
        return $this->container['terminal_location'];
    }

    /**
     * Sets terminal_location
     * @param \Gateway\Client\Model\TerminalLocation $terminal_location
     * @return $this
     */
    public function setTerminalLocation($terminal_location)
    {
        $this->container['terminal_location'] = $terminal_location;

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
     * Gets rated_origin_location
     * @return \Gateway\Client\Model\RatedOriginLocation
     */
    public function getRatedOriginLocation()
    {
        return $this->container['rated_origin_location'];
    }

    /**
     * Sets rated_origin_location
     * @param \Gateway\Client\Model\RatedOriginLocation $rated_origin_location
     * @return $this
     */
    public function setRatedOriginLocation($rated_origin_location)
    {
        $this->container['rated_origin_location'] = $rated_origin_location;

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
     * Gets packaged_items
     * @return \Gateway\Client\Model\PackagedItems[]
     */
    public function getPackagedItems()
    {
        return $this->container['packaged_items'];
    }

    /**
     * Sets packaged_items
     * @param \Gateway\Client\Model\PackagedItems[] $packaged_items
     * @return $this
     */
    public function setPackagedItems($packaged_items)
    {
        $this->container['packaged_items'] = $packaged_items;

        return $this;
    }

    /**
     * Gets pallet_count
     * @return int
     */
    public function getPalletCount()
    {
        return $this->container['pallet_count'];
    }

    /**
     * Sets pallet_count
     * @param int $pallet_count
     * @return $this
     */
    public function setPalletCount($pallet_count)
    {
        $this->container['pallet_count'] = $pallet_count;

        return $this;
    }

    /**
     * Gets documents
     * @return \Gateway\Client\Model\Documents[]
     */
    public function getDocuments()
    {
        return $this->container['documents'];
    }

    /**
     * Sets documents
     * @param \Gateway\Client\Model\Documents[] $documents
     * @return $this
     */
    public function setDocuments($documents)
    {
        $this->container['documents'] = $documents;

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
     * Gets plan_handling_value
     * @return string[]
     */
    public function getPlanHandlingValue()
    {
        return $this->container['plan_handling_value'];
    }

    /**
     * Sets plan_handling_value
     * @param string[] $plan_handling_value
     * @return $this
     */
    public function setPlanHandlingValue($plan_handling_value)
    {
        $this->container['plan_handling_value'] = $plan_handling_value;

        return $this;
    }

    /**
     * Gets plan_handling_notes
     * @return string
     */
    public function getPlanHandlingNotes()
    {
        return $this->container['plan_handling_notes'];
    }

    /**
     * Sets plan_handling_notes
     * @param string $plan_handling_notes
     * @return $this
     */
    public function setPlanHandlingNotes($plan_handling_notes)
    {
        $this->container['plan_handling_notes'] = $plan_handling_notes;

        return $this;
    }

    /**
     * Gets shipment_rate_type
     * @return string
     */
    public function getShipmentRateType()
    {
        return $this->container['shipment_rate_type'];
    }

    /**
     * Sets shipment_rate_type
     * @param string $shipment_rate_type
     * @return $this
     */
    public function setShipmentRateType($shipment_rate_type)
    {
        $this->container['shipment_rate_type'] = $shipment_rate_type;

        return $this;
    }

    /**
     * Gets shipment_load
     * @return string
     */
    public function getShipmentLoad()
    {
        return $this->container['shipment_load'];
    }

    /**
     * Sets shipment_load
     * @param string $shipment_load
     * @return $this
     */
    public function setShipmentLoad($shipment_load)
    {
        $this->container['shipment_load'] = $shipment_load;

        return $this;
    }

    /**
     * Gets quote_id
     * @return string
     */
    public function getQuoteId()
    {
        return $this->container['quote_id'];
    }

    /**
     * Sets quote_id
     * @param string $quote_id
     * @return $this
     */
    public function setQuoteId($quote_id)
    {
        $this->container['quote_id'] = $quote_id;

        return $this;
    }

    /**
     * Gets quote_source
     * @return string
     */
    public function getQuoteSource()
    {
        return $this->container['quote_source'];
    }

    /**
     * Sets quote_source
     * @param string $quote_source
     * @return $this
     */
    public function setQuoteSource($quote_source)
    {
        $this->container['quote_source'] = $quote_source;

        return $this;
    }

    /**
     * Gets tender_id
     * @return string
     */
    public function getTenderId()
    {
        return $this->container['tender_id'];
    }

    /**
     * Sets tender_id
     * @param string $tender_id
     * @return $this
     */
    public function setTenderId($tender_id)
    {
        $this->container['tender_id'] = $tender_id;

        return $this;
    }

    /**
     * Gets affiliated_tender_ids
     * @return string[]
     */
    public function getAffiliatedTenderIds()
    {
        return $this->container['affiliated_tender_ids'];
    }

    /**
     * Sets affiliated_tender_ids
     * @param string[] $affiliated_tender_ids
     * @return $this
     */
    public function setAffiliatedTenderIds($affiliated_tender_ids)
    {
        $this->container['affiliated_tender_ids'] = $affiliated_tender_ids;

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


