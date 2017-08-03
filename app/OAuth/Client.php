<?php
namespace App\OAuth;

use Illuminate\Support\Facades\Log;

class Client implements ClientInterface
{
    protected $client;

    protected $message;

    protected $base_url = '';

    protected $options = [];

    protected $body = '';

    protected $last_error_number = 0;

    protected $last_error = '';

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
     * @return string
     */
    public function getBody() : string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body)
    {
        $this->body = $body;
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
        return (array) ($this->options + $this->getDefaultOptions());
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
            throw new \OutOfBoundsException($request_type ." is not a valid request type.");
        }
        $this->setOption(CURLOPT_CUSTOMREQUEST, $request_type);

        $this->setUrl($url);
        // First set the url, because `setRequestTypeHeaderWithData` requires you to have a base url
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
        if ($this->getUrl() === "") {
            throw new \BadFunctionCallException(
                "Client::\$url is empty. to call `setRequestTypeHeaderWithData`, it is required."
            );
        }
        $converted_data = $this->convertRawData($data);
        switch ($request_type) {
            case 'POST':
            case 'PUT':
                $this->setOption(CURLOPT_POSTFIELDS, $converted_data);
                $this->setOption(constant("CURLOPT_" . $request_type), true);
                $this->setOption(CURLOPT_URL, $this->getUrl());
                break;
            case 'GET':
                $this->setOption(CURLOPT_URL, $this->getUrl() . "?" . $converted_data);
                break;
        }
        return $this;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return null|resource
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Returns the HTTP status code after request.
     *
     * @return string
     */
    public function getStatusCode()
    {
        return curl_getinfo($this->getMessage(), CURLINFO_HTTP_CODE);
    }

    /**
     * @return int
     */
    public function getLastErrorNumber() : int
    {
        return $this->last_error_number;
    }

    /**
     * @return string
     */
    public function getLastError(): string
    {
        return $this->last_error;
    }

    /**
     * @param string $last_error
     */
    public function setLastError(string $last_error)
    {
        $this->last_error = $last_error;
    }

    /**
     * @param int $last_error_number
     */
    public function setLastErrorNumber(int $last_error_number)
    {
        $this->last_error_number = $last_error_number;
    }

    public function __destruct()
    {
        if ($this->getMessage()) {
            curl_close($this->getMessage());
        }
    }

    /**
     * @return mixed
     */
    public function build()
    {
        $ch = curl_init();
        curl_setopt_array($ch, $this->getOptions());
        $this->setMessage($ch);
        return $this->getMessage();
    }

    public function get()
    {
        $message = $this->build();
        try {
            $curl_exec = curl_exec($message);
            $this->setBody($curl_exec);
            $this->setLastErrorNumber(curl_errno($message));
            $this->setLastError(curl_error($message));
        } catch (\Exception $excep) {
            Log::error($excep->getMessage() . " -- " . $excep->getFile() . ":" . $excep->getLine());
        }
        return $this;
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
