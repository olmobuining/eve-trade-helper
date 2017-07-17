<?php
namespace App\OAuth\ESI;


use App\Transaction;
use App\User;
use Illuminate\Support\Facades\Auth;

class Wallet extends ESI
{

    public static function getTransactions(int $character_id)
    {
        $transactions_uri = ESI::BASE_URI . '/characters/' . $character_id . '/wallet/transactions/';
        self::setLocation($transactions_uri);
        $user = User::whereCharacterId(Auth::user()->getAuthIdentifier())->first();
        self::addBearerAuthorization($user);
        $esi_array = self::send();
        return Transaction::esiToObjects($esi_array);
    }
}
