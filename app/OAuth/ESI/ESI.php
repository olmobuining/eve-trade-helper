<?php
namespace App\OAuth\ESI;

use App\OAuth\CurlCall;
use App\User;

class ESI extends CurlCall
{

    const BASE_URI = 'https://esi.tech.ccp.is/latest';

    /**
     * Add basic client ID and secret for authentication to the curl call.
     */
    protected static function setAuthentication()
    {
        curl_setopt(
            self::getCurl(),
            CURLOPT_USERPWD,
            env('EVE_APP_CLIENT_ID') . ":" . env('EVE_APP_CLIENT_SECRET')
        );
    }

    /**
     * Sets the header `Authorization` in the curl call with `Bearer {{access_token}}`
     * @param User $user
     */
    protected static function addBearerAuthorization(User $user)
    {
        curl_setopt(
            self::getCurl(),
            CURLOPT_HTTPHEADER,
            [
                'Authorization: Bearer ' . $user->access_token,
            ]
        );
    }
}
