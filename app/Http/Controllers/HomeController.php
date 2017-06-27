<?php

namespace App\Http\Controllers;

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

        return view('welcome', compact('character_name'));
    }
}
