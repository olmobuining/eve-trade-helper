<?php

namespace App\OAuth\ESI;

use App\Transaction;
use App\User;
use Illuminate\Support\Facades\Auth;

class Wallet extends ESI
{

    /**
     * @param int $character_id
     *
     * @return \App\called_class[]|array
     */
    public static function getTransactions(int $character_id)
    {
        $transactions_uri = ESI::BASE_URI . '/characters/' . $character_id . '/wallet/transactions/';

        $esi = new ESI();
        $user = User::whereCharacterId(Auth::user()->getAuthIdentifier())->first();
        $esi->setBearerAuthorization($user->access_token);
        $req = $esi->request(
            'GET',
            $transactions_uri
        )->get();
        $esi_array = json_decode($req->getBody());
        // Temp fix for the wallet API returning errors, should find a different fix.
        if (isset($esi_array->error)) {
            return [];
        }
        return Transaction::esiToObjects($esi_array);
    }
}
