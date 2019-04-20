<?php

namespace App\OAuth\ESI;

use App\Order;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class Market extends ESI
{
    /**
     * Get orders for the authenticated user aka character
     *
     * @param int $character_id An EVE character ID
     * @param int $cache_time   default 300 (5 minutes)
     *
     * @return Order[]
     */
    public static function getOrdersByCharacter(int $character_id, int $cache_time = 300)
    {
        $cache_key = 'Market.Orders.' . $character_id;
        if (!$esi_array = Redis::get($cache_key)) {
            $orders_uri = ESI::BASE_URI . '/characters/' . $character_id . '/orders/';

            $esi = new ESI();
            $user = User::whereCharacterId(Auth::user()->getAuthIdentifier())->first();
            $esi->setBearerAuthorization($user->access_token);
            $esi_array = json_decode($esi->request(
                'GET',
                $orders_uri
            )->get()->getBody());

            Redis::setex(
                $cache_key,
                $cache_time,
                serialize($esi_array)
            );
        } else {
            $esi_array = unserialize($esi_array);
        }

        if ($esi_array == false) {
            Redis::del($cache_key);
            return [];
        }
        /**
         * Add comment for PhpStorm.
         * Can't return right away, because otherwise PhpStorm will give a hint: unexpected return value.
         * @var $orders_col \App\Order[]
         */
        $orders_col = Order::esiToObjects($esi_array);
        return $orders_col;
    }

    /**
     * @param int    $region_id     Return orders in this region
     * @param int    $type_id       Return orders only for this type
     * @param int    $cache_time    Default 300 (5 minutes)
     * @param string $order_type    Filter buy/sell orders, return all orders by default.
     *                              If you query without type_id, we always return both buy and sell orders.
     *
     * @return Order[]
     */
    public static function getOrdersInRegionByTypeId(
        int $region_id,
        int $type_id,
        string $order_type = "buy",
        int $cache_time = 300
    ) {
        $cache_key = 'Market.Orders.Region.' . $region_id . "." . $order_type . ".Type." . $type_id;

        if (!$esi_array = Redis::get($cache_key)) {
            $orders_uri = ESI::BASE_URI . '/markets/' . $region_id . '/orders/';

            $esi = new ESI();
            $user = User::whereCharacterId(Auth::user()->getAuthIdentifier())->first();
            $esi->setBearerAuthorization($user->access_token);
            $esi_array = json_decode($esi->request(
                'GET',
                $orders_uri,
                ['type_id' => $type_id, "order_type" => $order_type]
            )->get()->getBody());


            Redis::setex(
                $cache_key,
                $cache_time,
                serialize($esi_array)
            );
        } else {
            $esi_array = unserialize($esi_array);
        }
        if ($esi_array === false) {
            Redis::del($cache_key);
            return [];
        }
        /**
         * Add comment for PhpStorm.
         * Can't return right away, because otherwise PhpStorm will give a hint: unexpected return value.
         * @var $esi_objects \App\Order[]
         */
        $esi_objects = Order::esiToObjects($esi_array);
        return $esi_objects;
    }

}
