<?php

namespace App;

use App\OAuth\ESI\Market;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    private static $mapping = [
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
     * @param array $orders
     * @return Order[]
     */
    public static function esiToObjects(array $orders)
    {
        $new_orders_collection = [];
        foreach ($orders as $order) {
            $new_orders_collection[] = self::esiInstanceToObject($order);
        }
        return $new_orders_collection;
    }

    /**
     * converts a single object to a Order object (normally from ESI)
     * @param $esi
     * @return Order
     */
    private static function esiInstanceToObject($esi)
    {
        $new_order = new Order();
        foreach (self::$mapping as $key => $map_to) {
            if (!empty($map_to) && property_exists($esi, $key)) {
                $new_order->$map_to = $esi->{$key};
            }
        }
        return $new_order;
    }

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
    public function getInventoryName()
    {
        if (!property_exists($this, 'type_name')) {
            $this->type_name = $this->type()->first()->getName();
        }
        return $this->type_name;
    }

    /**
     * @return Order[]
     */
    public function getCompetitionOrders()
    {
        return Market::getOrdersInRegionByTypeId($this->region_id, $this->type_id);
    }
}
