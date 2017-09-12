<?php

namespace App;

use App\OAuth\ESI\Market;

class Order extends ESIModel
{
    public static $mapping = [
        'account_id'       => null,
        "duration"         => 'duration',
        "escrow"           => 'escrow',
        "is_buy_order"     => 'is_buy_order',
        "is_corp"          => 'is_corp',
        "issued"           => 'issued',
        "location_id"      => 'location_id',
        "min_volume"       => 'min_volume',
        "order_id"         => 'order_id',
        "price"            => 'price',
        "range"            => 'range',
        "region_id"        => 'region_id',
        "state"            => 'state',
        "type_id"          => 'type_id',
        "volume_remain"    => 'volume_remain',
        "volume_total"     => 'volume_total',
    ];

    /**
     * Get the user that owns the phone.
     */
    public function type()
    {
        return $this->belongsTo('App\EveSDE\Inventory\Type', 'type_id', 'typeID');
    }

    /**
     * Return the inventory type name from the inv_type table and set it on this object, caches it on this object.
     * @return string
     */
    public function getInventoryName() : string
    {
        if (!property_exists($this, 'type_name')) {
            $type_object = $this->type()->first();
            if ($type_object === null) {
                return '';
            }
            $this->type_name = $type_object->getName();
        }
        return (string) $this->type_name;
    }

    /**
     * @return Order[]
     */
    public function getCompetitionOrders()
    {
        return Market::getOrdersInRegionByTypeId($this->region_id, $this->type_id, $this->getOrderType());
    }

    /**
     * @return bool
     */
    public function isBuyOrder() : bool
    {
        return (bool) $this->is_buy_order;
    }

    /**
     * @return string
     */
    public function getOrderType() : string
    {
        if ($this->isBuyOrder()) {
            return "buy";
        }
        return "sell";
    }

    /**
     * Gets prices from region 'The Forge' with a cached time of 1 day.
     * @return Order[]
     */
    public function getPriceInTheForge()
    {
        return Market::getOrdersInRegionByTypeId(10000002, $this->type_id, 'sell', 86400);
    }
}
