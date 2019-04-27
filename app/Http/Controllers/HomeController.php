<?php

namespace App\Http\Controllers;

use App\OAuth\ESI\Market;
use App\OAuth\ESI\UserInterface;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

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
        $transactions = $user->getWalletTransactions();
        $user = User::whereCharacterId(Auth::user()->getAuthIdentifier())->first();
        $orders = Market::getOrdersByCharacter($user->character_id);
        $total_sell = 0;
        $total_buy = 0;
        foreach ($orders as $order) {
            $total_name = 'total_' . $order->getOrderType();
            $$total_name += $order->price * $order->volume_remain;
        }

        $profit = 0;
        $sell_transactions = [];
        $buy_transactions = [];

        foreach ($transactions as $transaction) {
            $array_name = $transaction->getType() . '_transactions';
            ${$array_name}[$transaction->transaction_id] = $transaction;
        }
        foreach ($sell_transactions as $sell_transaction) {
            foreach ($buy_transactions as $key => $buy_transaction) {
                if ($sell_transaction->type_id === $buy_transaction->type_id) {
                    unset($buy_transactions[$key]);
                    $cost = $buy_transaction->quantity * $buy_transaction->unit_price;
                    $profit += ($sell_transaction->quantity * $sell_transaction->unit_price) - $cost;
                    break;
                }
            }
        }

        return view('welcome', compact('character_name', 'transactions', 'total_sell', 'total_buy', 'profit'));
    }

    public function refresh()
    {
        $user = User::whereCharacterId(Auth::user()->getAuthIdentifier())->first();
        Market::deleteOrderCacheForCharater($user->character_id);
        $cache_key = 'Market.Orders.' . $user->character_id . '.Full';
        Redis::del($cache_key);

        return 'success';
    }

    public function currentOrders()
    {
        $user = User::whereCharacterId(Auth::user()->getAuthIdentifier())->first();
        $cache_key = 'Market.Orders.' . $user->character_id . '.Full';
        if (!$orders = Redis::get($cache_key)) {
            $orders = Market::getOrdersByCharacter($user->character_id);
            foreach ($orders as $order) {
                $competition = $order->getCompetitionOrders();
                $order->outbid = false;
                $order->outbid_price = 0;
                foreach ($competition as $comp) {
                    if ($order->isBuyOrder()) {
                        if ($comp->price > $order->price || ($order->outbid && $order->outbid_price < $comp->price)) {
                            $order->outbid = true;
                            $order->outbid_price = $comp->price;
                        }
                    } else {
                        if ($comp->price < $order->price || ($order->outbid && $order->outbid_price > $comp->price)) {
                            $order->outbid = true;
                            $order->outbid_price = $comp->price;
                        }
                    }
                }

                $order->type = strtoupper($order->getOrderType());
                $order->forge_price = 0;
                $order->inventory_name = '<a href="javascript:openMarket(' . $order->type_id . ')">' . $order->getInventoryName() . '</a>';
                $theforge = $order->getPriceInTheForge($order->getOrderType());
                foreach ($theforge as $forge) {
                    if ($order->isBuyOrder()) {
                        if ($order->forge_price < $forge->price || $order->forge_price == 0) {
                            $order->forge_price = $forge->price;
                        }
                    } else {
                        if ($order->forge_price > $forge->price || $order->forge_price == 0) {
                            $order->forge_price = $forge->price;
                        }
                    }
                }
                $order->forge_price = number_format($order->forge_price, 2, ",", ".");
                $order->outbid_price = number_format($order->outbid_price, 2, ",", ".");
                $order->price_order = $order->price;
                $order->price = number_format($order->price, 2, ",", ".");
            }
            Redis::setex(
                $cache_key,
                300,
                serialize($orders)
            );
        } else {
            $orders = unserialize($orders);
        }

        return ['data' => $orders];
    }

    public function openMarket($tyoe_id)
    {
        return UserInterface::openMarket($tyoe_id);
    }
}
