<?php

namespace App\OAuth\ESI;

class Authentication extends ESI
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
        self::setLocation(self::AUTHORIZATION_URI);
        self::setPost();
        self::setAuthentication();
        self::setPostValues([
            'grant_type' => 'authorization_code',
            'code'       => $auth_code,
        ]);
        return self::send();
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
        self::setLocation(self::VERIFY_URI);
        self::setBearerAuthorization($access_token);
        return self::send();
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
        self::setLocation(self::AUTHORIZATION_URI);
        self::setAuthentication();
        self::setPostValues([
            'grant_type'          => 'refresh_token',
            'refresh_token'       => $refresh_token,
        ]);
        return self::send();
    }
}
