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
     * @param int   $character_id
     * @param int   $cache_time   default 300 (5 minutes)
     * @return Order[]
     */
    public static function getOrdersByCharacter(int $character_id, int $cache_time = 300)
    {
        $cache_key = 'Market.Orders.' . $character_id;
        if (!$esi_array = Redis::get($cache_key)) {
            $orders_uri = ESI::BASE_URI . '/characters/' . $character_id . '/orders/';
            self::setLocation($orders_uri);
            $user = User::whereCharacterId(Auth::user()->getAuthIdentifier())->first();
            self::addBearerAuthorization($user);
            $esi_array = self::send();

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
        $orders_col = Order::esiToObjects($esi_array);

        return $orders_col;
    }

    /**
     * @param int $region_id
     * @param int $type_id
     * @param int $cache_time   default 300 (5 minutes)
     * @param string $order_type    'buy' or 'sell'
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
            self::setLocation($orders_uri);
            $user = User::whereCharacterId(Auth::user()->getAuthIdentifier())->first();
            self::addBearerAuthorization($user);
            self::setGetValues(['type_id' => $type_id, "order_type" => $order_type]);
            $esi_array = self::send();

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
        $orders_col = Order::esiToObjects($esi_array);

        return $orders_col;
    }

}
