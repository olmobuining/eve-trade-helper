<?php

namespace App\OAuth\ESI;

class Authentication
{
    const AUTHORIZATION_URI = 'https://login.eveonline.com/oauth/token';
    const VERIFY_URI        = 'https://login.eveonline.com/oauth/verify';

    /**
     * Does the call to get the accesstoken and such.. need to improve.
     * @param string $auth_code
     * @return array|bool|mixed
     */
    public static function verifyAuthorizationCode(string $auth_code)
    {
        if (empty($auth_code)) {
            throw new \InvalidArgumentException('Authorization code cannot be empty.');
        }
        $esi = new ESI();
        $esi->setAuthentication();
        return json_decode($esi->request(
            'POST',
            self::AUTHORIZATION_URI,
            [
                'grant_type' => 'authorization_code',
                'code'       => $auth_code,
            ]
        )->get()->getBody());
    }

    /**
     * @param string $access_token
     * @return array|bool|mixed
     */
    public static function verifyAccessToken(string $access_token)
    {
        if (empty($access_token)) {
            throw new \InvalidArgumentException('Access token cannot be empty.');
        }

        $esi = new ESI();
        $esi->setBearerAuthorization($access_token);
        return json_decode($esi->request(
            'GET',
            self::VERIFY_URI
        )->get()->getBody());
    }

    /**
     * @param string $refresh_token
     * @return array|bool|mixed
     */
    public static function refreshAccessToken(string $refresh_token)
    {
        if (empty($refresh_token)) {
            throw new \InvalidArgumentException('Access token cannot be empty.');
        }

        $esi = new ESI();
        $esi->setAuthentication();
        return json_decode($esi->request(
            'POST',
            self::AUTHORIZATION_URI,
            [
                'grant_type'          => 'refresh_token',
                'refresh_token'       => $refresh_token,
            ]
        )->get()->getBody());
    }
}
