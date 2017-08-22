<?php
namespace App\OAuth\ESI;

use App\OAuth\Client;
use App\User;
use Illuminate\Support\Facades\Auth;

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

    public function get()
    {
        $get = parent::get();
        $body = json_decode($get->getBody());
        if ($body
            && isset($body->error)
            && $body->error === "expired"
            && $body->sso_status == 400
        ) {
            $user = User::whereCharacterId(Auth::user()->getAuthIdentifier())->first();
            $user->refreshAccessToken();
            return parent::get();
        }
        return $get;
    }
}
