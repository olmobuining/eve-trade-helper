<?php
namespace App\OAuth;

class Client implements ClientInterface
{
    protected $client;

    protected $options = [];

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param $client
     *
     * @return $this
     */
    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return array
     */
    public function getDefaultOptions() : array
    {
        return [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HEADER => false,
        ];
    }

    /**
     * @param $header
     * @param $value
     *
     * @return $this
     */
    public function setOption($header, $value)
    {
        $this->options[$header] = $value;
        return $this;
    }

    /**
     * @param bool $bool
     *
     * @return $this
     */
    public function setPostHeader(bool $bool = true)
    {
        $this->options[CURLOPT_POST] = $bool;
        return $this;
    }

    public function setPostFields(array $post_data)
    {
        $this->setOption(CURLOPT_POSTFIELDS, $post_data);
        return $this;
    }

    public function setUrl(string $url)
    {
        $this->setOption(CURLOPT_URL, $url);
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions() : array
    {
        return $this->options;
    }

    /**
     * Overwrites the options.
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function convertRawData(array $data) : string
    {
        return rawurldecode(http_build_query($data));
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function isValidRequestType(string $type) : bool
    {
        return (bool) in_array($type, self::REQUEST_TYPES);
    }

    public function request(string $request_type, string $url, array $data = [])
    {
        // Force a all capital string.
        $request_type = strtoupper($request_type);
        if ($this->isValidRequestType($request_type) === false) {
            throw new \InvalidArgumentException($request_type ." is not a valid request type.");
        }
        $this->setOption(CURLOPT_CUSTOMREQUEST, $request_type);

        $this->setUrl($url);
    }

    public function get()
    {
        //
    }

    /**
     * @param $user
     * @param $password
     *
     * @return $this
     */
    public function setBasicAuth($user, $password)
    {
        if (empty($user) || empty($password)) {
            throw new \InvalidArgumentException("Basic authentication needs a username and password.");
        }
        $this->options[CURLOPT_USERPWD] = $user . ":" . $password;
        return $this;
    }
}
