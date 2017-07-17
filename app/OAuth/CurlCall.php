<?php
namespace App\OAuth;

use Illuminate\Support\Facades\Log;

class CurlCall
{
    private static $curl               = '';
    private static $location           = '';
    private static $post_values        = '';
    private static $get_values         = '';

    /**
     * @return resource
     * @throws \Exception
     */
    protected static function getCurl()
    {
        $location = self::getLocation();
        if (empty($location)) {
            throw new \Exception(
                'Always set the location first, with self::setLocation($uri);'
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
        // After setting a different location, undo the previous curl instance.
        if (self::$curl != '') {
            self::$curl = '';
        }
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
        self::setPost();

        curl_setopt(self::getCurl(), CURLOPT_POSTFIELDS, rawurldecode(http_build_query(self::$post_values)));
    }

    /**
     * Sets and overwrites the post values.
     * @param array $post_array
     * @return bool
     */
    protected static function setGetValues($get_array = [])
    {
        if (!is_array($get_array)) {
            return false;
        }
        self::$get_values = $get_array;

        curl_setopt(
            self::getCurl(),
            CURLOPT_URL,
            self::getLocation() . "?" . rawurldecode(http_build_query(self::$get_values))
        );
    }

    /**
     * @return bool|mixed
     */
    protected static function send($override_curl = null)
    {
        $curl_instance = $override_curl;
        if (is_null($curl_instance)) {
            $curl_instance = self::getCurl();
        }
        try {
            $data = json_decode(curl_exec($curl_instance));
            curl_close($curl_instance);
            return $data;
        } catch (\Exception $excep) {
            Log::error($excep->getMessage() . " -- " . $excep->getFile() . ":" . $excep->getLine());
            return false;
        }
    }
}
