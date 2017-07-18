<?php
namespace App\OAuth;

class Client implements ClientInterface
{
    protected $client;

    protected $base_url = '';

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

    /**
     * @param array $post_data
     *
     * @return $this
     */
    public function setPostFields(array $post_data)
    {
        $this->setOption(CURLOPT_POSTFIELDS, $post_data);
        return $this;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl(string $url)
    {
        $this->base_url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl() : string
    {
        return $this->base_url;
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

    /**
     * @param string $request_type
     * @param string $url
     * @param array  $data
     *
     * @return $this
     */
    public function request(string $request_type, string $url, array $data = [])
    {
        // Force a all capital string.
        $request_type = strtoupper($request_type);
        if ($this->isValidRequestType($request_type) === false) {
            throw new \InvalidArgumentException($request_type ." is not a valid request type.");
        }
        $this->setOption(CURLOPT_CUSTOMREQUEST, $request_type);

        $this->setUrl($url);
        $this->setRequestTypeHeaderWithData($request_type, $data);
        return $this;
    }

    /**
     * @param string $request_type
     * @param array  $data
     *
     * @return $this
     */
    private function setRequestTypeHeaderWithData(string $request_type, array $data)
    {
        $converted_data = $this->convertRawData($data);
        switch ($request_type) {
            case 'POST':
            case 'PUT':
                $this->setOption(CURLOPT_POSTFIELDS, $converted_data);
                $this->setOption(constant("CURLOPT_" . $request_type), true);
                break;
            case 'GET':
                $this->setOption(CURLOPT_URL, $url . "?" . $converted_data);
                break;
        }
        return $this;
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
