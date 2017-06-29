<?php

namespace App\OAuth\ESI;

class Authentication extends ESI
{
    const AUTHORIZATION_URI = 'https://login.eveonline.com/oauth/token';
    const VERIFY_URI        = 'https://login.eveonline.com/oauth/verify';

    /**
     * Does the call to get the accesstoken and such.. need to improve.
     * @param $auth_code
     * @return bool|mixed
     */
    public static function verifyAuthorizationCode($auth_code)
    {
        if (empty($auth_code)) {
            throw new \InvalidArgumentException('Authorization code cannot be empty.');
        }
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
        self::setLocation(self::VERIFY_URI);
        self::setBearerAuthorization($access_token);
        return self::callCurl();
    }

    public static function refreshAccessToken()
    {
        //
    }
}
