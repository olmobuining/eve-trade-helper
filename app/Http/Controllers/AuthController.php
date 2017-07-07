<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $unique_hash = uniqid('ETH');
        $request->session()->put('hash_state_input', $unique_hash);
        $state = Crypt::encrypt($unique_hash);
        $client_id = env('EVE_APP_CLIENT_ID');

        return view('login', compact('state', 'client_id'));
    }

    public function logout()
    {
        Auth::logout();
        return Redirect::route('login');
    }
    /**
     * EVE Swagger Interface returns the user to this URL after login and authorisation.
     */
    public function callback(Request $request)
    {
        $code = $request->get('code');
        $state = $request->get('state');
        $unique_hash = $request->session()->get('hash_state_input');
        $state_decrypted = Crypt::decrypt($state);

        if ($unique_hash !== $state_decrypted) {
            return response()->view('errors.400', [], 400);
        }
        $user = User::where('authorization_code', $code)->first();
        if ($user === null) {
            $user = new User();
            $user->authorization_code = $code;
            $user->save();
        }
        $verified = $user->verifyAuthorizationCode();
        if ($verified === false) {
            \Session::flash('flash_message', 'Verification of authorization code failed, please try again.');
            return Redirect::to('login');
        }
        $character_data_received = $user->getCharacterData();
        if ($character_data_received === false) {
            \Session::flash('flash_message', 'Could not receive character data, please try again.');
            return Redirect::to('login');
        }

        return Redirect::to('/');
    }
}
