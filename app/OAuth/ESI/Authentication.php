<?php

namespace App\OAuth\ESI;

class Authentication extends ESI
{
    const AUTHORIZATION_URI = 'https://login.eveonline.com/oauth/token';
    const VERIFY_URI        = 'https://login.eveonline.com/oauth/token';

    /**
     * Does the call to get the accesstoken and such.. need to improve.
     * @param $auth_code
     * @return bool|mixed
     */
    public static function verifyAuthorizationCode($auth_code)
    {
        self::setLocation(self::AUTHORIZATION_URI);
        self::setPost();
        self::setAuthentication();
        self::setPostValues([
            'grant_type' => 'authorization_code',
            'code'       => $auth_code,
        ]);
        return self::callCurl();
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
        //
    }
}
