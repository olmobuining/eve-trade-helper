<?php

namespace App\OAuth\ESI;

class Authentication
{
    /**
     * Does the call to get the accesstoken and such.. need to improve.
     * @param $auth_code
     * @return bool|mixed
     */
    public static function verifyAuthorizationCode($auth_code)
    {
        try {
            $curl = curl_init('https://login.eveonline.com/oauth/token');
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_USERPWD, env('EVE_APP_CLIENT_ID') . ":" . env('EVE_APP_CLIENT_SECRET'));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, rawurldecode(http_build_query([
                'grant_type' => 'authorization_code',
                'code' => $auth_code,
            ])));
            $data = json_decode(curl_exec($curl));
            if (!empty($data->error)) {
                return false;
            }
            return $data;
        } catch (\Exception $excep) {
            return false;
        }
    }

    public static function verifyAccessToken($access_token)
    {
        if (empty($access_token)) {
            throw new \InvalidArgumentException('Access token cannot be empty.');
        }

        try {
            $curl = curl_init('https://login.eveonline.com/oauth/verify');
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $access_token,
            ]);

            $data = json_decode(curl_exec($curl));
            if (!empty($data->error)) {
                return false;
            }
            return $data;
        } catch (\Exception $excep) {
            return false;
        }
    }

    public static function refreshAccessToken()
    {

    }
}
