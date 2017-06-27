<?php

namespace App\OAuth\ESI;

class Authentication
{
    private static $curl               = '';
    private static $location           = '';
    private static $post_values        = '';

    const AUTHORIZATION_URI = 'https://login.eveonline.com/oauth/token';
    const VERIFY_URI        = 'https://login.eveonline.com/oauth/token';

    private static function getCurl()
    {
        $location = self::getLocation();
        if (empty($location)) {
            throw new \Exception(
                'Always set the location first, with \App\OAuth\ESI\Authentication::setLocation($uri); (private)'
            );
        }
        if (self::$curl == '') {
            self::$curl = curl_init($location);
            curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(self::$curl, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt(self::$curl, CURLOPT_HEADER, false);
            curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, true);
        }
        return self::$curl;
    }

    /**
     * @param string $uri
     * @return string
     */
    private static function setLocation($uri)
    {
        self::$location = $uri;
    }

    /**
     * @return null|string
     */
    private static function getLocation()
    {
        return self::$location;
    }

    private static function setPost($bool = true)
    {
        $bool = (bool)$bool;
        curl_setopt(self::getCurl(), CURLOPT_POST, $bool);
    }

    private static function setAuthentication()
    {
        curl_setopt(
            self::getCurl(),
            CURLOPT_USERPWD,
            env('EVE_APP_CLIENT_ID') . ":" . env('EVE_APP_CLIENT_SECRET')
        );
    }

    /**
     * Sets and overwrites the post values.
     * @param array $post_array
     * @return bool
     */
    private static function setPostValues($post_array = [])
    {
        if (!is_array($post_array)) {
            return false;
        }
        self::$post_values = $post_array;

        curl_setopt(self::getCurl(), CURLOPT_POSTFIELDS, rawurldecode(http_build_query(self::$post_values)));
    }

    private static function callCurl()
    {
        try {
            $data = json_decode(curl_exec(self::getCurl()));
            if (!empty($data->error)) {
                curl_close(self::getCurl());
                return false;
            }
            curl_close(self::getCurl());
            return $data;
        } catch (\Exception $excep) {
            curl_close(self::getCurl());
            return false;
        }
        curl_close(self::getCurl());
        return false;
    }

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
