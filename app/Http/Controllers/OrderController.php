<?php

namespace App\Http\Controllers;

use App\OAuth\ESI\Market;
use App\User;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function current()
    {
        $user = User::whereCharacterId(Auth::user()->getAuthIdentifier())->first();
        $orders = Market::getOrdersByCharacter($user->character_id);
        foreach ($orders as $order) {
            $competition = $order->getCompetitionOrders();
            $order->outbid = false;
            $order->outbid_price = 0;
            foreach ($competition as $comp) {
                if ($comp->price > $order->price || ($order->outbid && $order->outbid_price < $comp->price)) {
                    $order->outbid = true;
                    $order->outbid_price = $comp->price;
                }
            }
            $order->type = strtoupper($order->getOrderType());
            $order->forge_price = 0;
            $order->inventory_name = $order->getInventoryName();
            $theforge = $order->getPriceInTheForge();
            foreach ($theforge as $forge) {
                if ($order->forge_price > $forge->price || $order->forge_price == 0) {
                    $order->forge_price = $forge->price;
                }
            }
            $order->forge_price = number_format($order->forge_price, 2, ",", ".");
            $order->outbid_price = number_format($order->outbid_price, 2, ",", ".");
            $order->price_order = $order->price;
            $order->price = number_format($order->price, 2, ",", ".");
        }
        return ['data' => $orders];
    }
}
