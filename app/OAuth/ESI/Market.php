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
     * @return Order[]
     */
    public static function getOrdersByCharacter(int $character_id)
    {
        if (!$esi_array = Redis::get('Market.Orders.' . $character_id)) {
            $orders_uri = ESI::BASE_URI . '/characters/' . $character_id . '/orders/';
            self::setLocation($orders_uri);
            $user = User::whereCharacterId(Auth::user()->getAuthIdentifier())->first();
            self::addBearerAuthorization($user);
            $esi_array = self::send();
            Redis::setex('Market.Orders.' . $character_id, 300, serialize($esi_array));
        } else {
            $esi_array = unserialize($esi_array);
        }
        $orders_col = Order::esiToObjects($esi_array);

        return $orders_col;
    }

    /**
     * @param int $region_id
     * @param int $type_id
     * @return Order[]
     */
    public static function getOrdersInRegionByTypeId(int $region_id, int $type_id)
    {
        if (!$esi_array = Redis::get('Market.Orders.Region.' . $region_id . ".Type." . $type_id)) {
            $orders_uri = ESI::BASE_URI . '/markets/' . $region_id . '/orders/';
            self::setLocation($orders_uri);
            $user = User::whereCharacterId(Auth::user()->getAuthIdentifier())->first();
            self::addBearerAuthorization($user);
            self::setGetValues(['type_id' => $type_id, "order_type" => 'buy']);
            $esi_array = self::send();

            Redis::setex('Market.Orders.Region.' . $region_id . ".Type." . $type_id, 300, serialize($esi_array));
        } else {
            $esi_array = unserialize($esi_array);
        }
        $orders_col = Order::esiToObjects($esi_array);

        return $orders_col;
    }

}
