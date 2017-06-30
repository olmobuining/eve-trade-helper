<?php
namespace App\OAuth\ESI;

use App\Order;
use App\User;

class Market extends ESI
{
    /**
     * Get orders for the given user aka character
     * @param User $user
     */
    public static function getOrdersByCharacter(User $user)
    {
        $orders_uri = ESI::BASE_URI . '/characters/' . $user->character_id . '/orders/';
        self::setLocation($orders_uri);
        self::addBearerAuthorization($user);
        $esi_array = self::send();
        return Order::esiToObjects($esi_array);
    }
}
