<?php
namespace App\OAuth;

class CurlCall
{
    private static $curl               = '';
    private static $location           = '';
    private static $post_values        = '';

    protected static function getCurl()
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
    protected static function setLocation($uri)
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

    protected static function setPost($bool = true)
    {
        $bool = (bool)$bool;
        curl_setopt(self::getCurl(), CURLOPT_POST, $bool);
    }

    /**
     * Sets and overwrites the post values.
     * @param array $post_array
     * @return bool
     */
    protected static function setPostValues($post_array = [])
    {
        if (!is_array($post_array)) {
            return false;
        }
        self::$post_values = $post_array;

        curl_setopt(self::getCurl(), CURLOPT_POSTFIELDS, rawurldecode(http_build_query(self::$post_values)));
    }

    protected static function callCurl()
    {
        try {
            $data = json_decode(curl_exec(self::getCurl()));
            if (!empty($data->error)) {
                dd($data);
                curl_close(self::getCurl());
                return false;
            }
            curl_close(self::getCurl());
            return $data;
        } catch (\Exception $excep) {
            dd($excep->getMessage());
            curl_close(self::getCurl());
            return false;
        }
    }
}
