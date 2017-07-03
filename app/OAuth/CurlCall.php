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

    protected static function send()
    {
        try {
            $data = json_decode(curl_exec(self::getCurl()));
            if (!empty($data->error)) {
                curl_close(self::getCurl());
                dd($data); // Just for the test fase (@todo remove before prod tests)
                return false;
            }
            curl_close(self::getCurl());
            return $data;
        } catch (\Exception $excep) {
            curl_close(self::getCurl());
            dd($excep->getMessage()); // ust for the test fase (@todo remove before prod tests)
            return false;
        }
    }
}
