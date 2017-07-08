<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    private static $mapping = [
        "transaction_id" => "transaction_id",
        "date"           => "date",
        "location_id"    => "location_id",
        "type_id"        => "type_id",
        "unit_price"     => "unit_price",
        "quantity"       => "quantity",
        "client_id"      => "client_id",
        "is_buy"         => "is_buy",
        "is_personal"    => "is_personal",
        "journal_ref_id" => "journal_ref_id",
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
     * @return Transaction
     */
    private static function esiInstanceToObject($esi)
    {
        $new_transaction = new Transaction();
        foreach (self::$mapping as $key => $map_to) {
            if (!empty($map_to) && property_exists($esi, $key)) {
                $new_transaction->$map_to = $esi->{$key};
            }
        }
        return $new_transaction;
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
}
