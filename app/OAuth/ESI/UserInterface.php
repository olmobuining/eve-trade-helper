<?php

namespace App\OAuth\ESI;


use App\User;
use Illuminate\Support\Facades\Auth;

class UserInterface
{
    public static function openMarket($type_id)
    {
        $uri  = ESI::BASE_URI . '/ui/openwindow/marketdetails/';

        $esi = new ESI();
        $user = User::whereCharacterId(Auth::user()->getAuthIdentifier())->first();
        $esi->setBearerAuthorization($user->access_token);
        $request = $esi->request(
            'POST',
            $uri . '?type_id=' . $type_id
        )->get();

        if ($request->getStatusCode() === 403) {
            $user->refreshAccessToken();
            $request = $esi->request(
                'POST',
                $uri . '?type_id=' . $type_id
            )->get();
        }

        return json_decode($request->getBody(), true);
    }
}
