<?php

namespace App\Http\Controllers;

use App\OAuth\ESI\Market;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $user = User::whereCharacterId(Auth::user()->getAuthIdentifier())->first();
        $character_name = $user->character_name;
        $orders = Market::getOrdersByCharacter($user->character_id);
        foreach ($orders as $order) {
            $competition = $order->getCompetitionOrders();
            $order->outbid = false;
            foreach ($competition as $comp) {
                if ($comp->price > $order->price || ($order->outbid && $order->outbid_price < $comp->price)) {
                    $order->outbid = true;
                    $order->outbid_price = $comp->price;
                }
            }
        }

        $transactions = $user->getWalletTransactions();

        return view('welcome', compact('character_name', 'orders', 'transactions'));
    }
}
