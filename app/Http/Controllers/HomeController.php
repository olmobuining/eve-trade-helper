<?php

namespace App\Http\Controllers;

use App\OAuth\ESI\Market;
use App\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $this->middleware('auth');
        $user = User::whereCharacterId(Auth::user()->getAuthIdentifier())->first();
        $character_name = $user->character_name;

        $orders = Market::getOrdersByCharacter($user->character_id);

        return view('welcome', compact('character_name', 'orders'));
    }
}
