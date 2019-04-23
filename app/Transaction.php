<?php

namespace App;

class Transaction extends ESIModel
{
    public static $mapping = [
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
     * @return bool
     */
    public function isBuyOrder() : bool
    {
        return (bool) $this->is_buy;
    }

    /**
     * @return string
     */
    public function getType() : string
    {
        if ($this->isBuyOrder()) {
            return 'buy';
        }

        return 'sell';
    }
}
