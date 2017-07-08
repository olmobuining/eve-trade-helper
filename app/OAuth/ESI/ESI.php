<?php
namespace App\OAuth\ESI;

use App\OAuth\CurlCall;
use App\User;
use Illuminate\Support\Facades\Auth;

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
        self::setBearerAuthorization($user->access_token);
    }

    /**
     * Sets the header `Authorization` in the curl call with `Bearer {{access_token}}`
     * @param string $access_token
     */
    protected static function setBearerAuthorization($access_token)
    {
        curl_setopt(
            self::getCurl(),
            CURLOPT_HTTPHEADER,
            [
                'Authorization: Bearer ' . $access_token,
            ]
        );
    }

    /**
     * overrule send method. This will force refresh token, if it has the correct error token.
     * returns an empty array if something else is wrong.
     * @return array|bool|mixed
     */
    public static function send()
    {
        $data = parent::send();
        if ($data !== false && isset($data->error)) {
            if ($data->error === "SSO responded with a 400: expired") {
                $user = User::whereCharacterId(Auth::user()->getAuthIdentifier())->first();
                $user->refreshAccessToken();
                $data = parent::send();
            } elseif (isset($data->error)) {
                $data = false;
            }
        }
        return $data;
    }
}
