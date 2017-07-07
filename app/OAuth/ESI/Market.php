<?php
namespace App\OAuth\ESI;

use App\Order;
use App\User;
use Illuminate\Support\Facades\Auth;

class Market extends ESI
{
    /**
     * Get orders for the authenticated user aka character
     * @param int   $character_id
     * @return Order[]
     */
    public static function getOrdersByCharacter(int $character_id)
    {
        $orders_uri = ESI::BASE_URI . '/characters/' . $character_id . '/orders/';
        self::setLocation($orders_uri);
        $user = User::whereCharacterId(Auth::user()->getAuthIdentifier())->first();
        self::addBearerAuthorization($user);
        $esi_array = self::send();
        return Order::esiToObjects($esi_array);
    }

    public static function getOrdersInRegionByTypeId(int $region_id, int $type_id)
    {
        $orders_uri = ESI::BASE_URI . '/markets/' . $region_id . '/orders/';
        self::setLocation($orders_uri);
        $user = User::whereCharacterId(Auth::user()->getAuthIdentifier())->first();
        self::addBearerAuthorization($user);
        self::setGetValues(['type_id' => $type_id, "order_type" => 'buy']);
        $esi_array = self::send();

        return Order::esiToObjects($esi_array);
    }

}
