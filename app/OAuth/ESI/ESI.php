<?php
namespace App\OAuth\ESI;

use App\OAuth\Client;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ESI extends Client
{
    const BASE_URI = 'https://esi.tech.ccp.is/latest';

    /**
     * Add basic client ID and secret for authentication to the curl call.
     */
    public function setAuthentication()
    {
        $this->setOption(
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
    public function setBearerAuthorization($access_token)
    {
        $this->setOption(
            CURLOPT_HTTPHEADER,
            [
                'Authorization: Bearer ' . $access_token,
            ]
        );
    }

    /**
     * overrule send method. This will force refresh token, if it has the correct error token.
     * returns an empty array if something else is wrong.
     * @return array|bool
     */
    public static function send($override_curl = null)
    {
        $data = parent::send();
        if ($data !== false && isset($data->error)) {
            if ($data->error === "SSO responded with a 400: expired") {
                $save_curl = self::getCurl();
                $user = User::whereCharacterId(Auth::user()->getAuthIdentifier())->first();
                $user->refreshAccessToken();
                $data = parent::send($save_curl);
                Log::error(
                    "Error 400 expired",
                    [
                        "data" => $data,
                        "get_called_class" => get_called_class(),
                    ]
                );
            } elseif (isset($data->error)) {
                Log::error(
                    "Error sending message",
                    [
                        "data" => $data,
                        "get_called_class" => get_called_class(),
                    ]
                );
                $data = false;
            }
        }
        return $data;
    }
}
