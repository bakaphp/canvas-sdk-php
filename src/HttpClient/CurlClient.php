<?php

namespace Kanvas\Sdk\HttpClient;

use Exception;

class CurlClient
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';
    const METHOD_HEAD = 'HEAD';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_CONNECT = 'CONNECT';
    const METHOD_TRACE = 'TRACE';

    /**
     * @var string The kanvas API key to be used for requests.
     */
    public $apiKey = null;

    /**
     * @var string The kanvas client_id to be used for Connect requests.
     */
    public $clientId = null;

    /**
     * @var string The kanvas client_secret_id to be used for Connect requests.
     */
    public $clientSecretId = null;

    /**
     * @var string Authentication Token
     */
    public $authToken = null;

    /**
     * @var CurlClient instance of self.
     */
    private static $instance = null;

    /**
     * Is Self Signed Certificates Allowed?
     *
     * @var bool
     */
    protected $selfSigned = false;

    /**
     * Service host name.
     *
     * @var string
     */
    protected $endpoint = 'https://apidev.kanvas.dev';

    /**
     * Global Headers.
     *
     * @var array
     */
    protected $headers = [
        'Content-Type' => 'application/x-www-form-urlencoded',
    ];

    /**
     * Variable to identify if endpoint call is auth.
     */
    protected $isAuth = false;

    /**
     * API Version.
     */
    protected $apiVersion = 'v1';

    /**
     * Get instance of self.
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new CurlClient();
        }

        return self::$instance;
    }

    /**
     * Sets custom request header  to be used in authetication.
     *
     * @param string $endpoint
     *
     * @return array
     */
    public function processCustomHeaders() : array
    {
        if ($this->isAuth) {
            return [
                'KanvasKey' => $this->getApiKey(),
                'Content-Type' => 'application/x-www-form-urlencoded'
            ];
        } elseif (is_null($this->getClientId()) && is_null($this->getClientSecretId())) {
            return [
                'Authorization' => $this->getAuthToken(),
                'KanvasKey' => $this->getApiKey(),
                'Content-Type' => 'application/x-www-form-urlencoded'
            ];
        } else {
            return [
                'Client-Id' => $this->getClientId(),
                'Client-Secret-Id' => $this->getClientSecretId(),
                'KanvasKey' => $this->getApiKey(),
                'Content-Type' => 'application/x-www-form-urlencoded'
            ];
        }
    }

    /**
     * Sets the API key to be used for requests.
     *
     * @param string $apiKey
     *
     * @return void
     */
    public function setApiKey(string $apiKey) : void
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Sets the client_id to be used for Connect requests.
     *
     * @param string $clientId
     *
     * @return void
     */
    public function setClientId(string $clientId) : void
    {
        $this->clientId = $clientId;
    }

    /**
     * Sets the client_secret_id to be used for Connect requests.
     *
     * @param string $clientSecretId
     *
     * @return void
     */
    public function setClienSecrettId(string $clientSecretId) : void
    {
        $this->clientSecretId = $clientSecretId;
    }

    /**
     * Sets Authentication Token.
     *
     * @param string $authToken
     *
     * @return void
     */
    public function setAuthToken(string $authToken) : void
    {
        $this->authToken = $authToken;
    }

    /**
     * @return string The API key used for requests.
     */
    public function getApiKey() : ?string
    {
        return $this->apiKey;
    }

    /**
     * @return string The client_id used for Connect requests.
     */
    public function getClientId() : ?string
    {
        return $this->clientId;
    }

    /**
     * @return string The client_secret_id used for Connect requests.
     */
    public function getClientSecretId() : ?string
    {
        return $this->clientSecretId;
    }

    /**
     * Sets Authentication Token.
     *
     * @param string $authToken
     *
     * @return void
     */
    public function getAuthToken() : ?string
    {
        return $this->authToken;
    }

    /**
     * Set Project.
     *
     * Your project ID
     *
     * @param string $value
     *
     * @return Client
     */
    public function setProject($value)
    {
        $this->addHeader('X-Appwrite-Project', $value);

        return $this;
    }

    /**
     * Set Key.
     *
     * Your secret API key
     *
     * @param string $value
     *
     * @return Client
     */
    public function setKey($value)
    {
        $this->addHeader('X-Appwrite-Key', $value);

        return $this;
    }

    /**
     * Set Locale.
     *
     * @param string $value
     *
     * @return Client
     */
    public function setLocale($value)
    {
        $this->addHeader('X-Appwrite-Locale', $value);

        return $this;
    }

    /***
     * @param bool $status
     * @return $this
     */
    public function setSelfSigned($status = true)
    {
        $this->selfSigned = $status;

        return $this;
    }

    /***
     * @param $endpoint
     * @return $this
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * @param $key
     * @param $value
     */
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Call.
     *
     * Make an API call
     *
     * @param string $method
     * @param string $path
     * @param array $params
     * @param array $headers
     *
     * @return array|string
     *
     * @throws Exception
     */
    public function call($method, $path = '', $headers = [], array $params = [])
    {
        if (is_null($this->getApiKey())) {
            throw new Exception('Api Key not set');
        }

        if ($path == 'auth') {
            $this->isAuth = 1;
        }

        $this->headers = $this->processCustomHeaders();
        $headers = array_merge($this->headers, $headers);

        $url = $this->endpoint . '/' . $this->apiVersion . '/' . $path;

        $ch = curl_init($url . (($method == self::METHOD_GET && !empty($params)) ? '?' . http_build_query($params) : ''));
        $responseHeaders = [];
        $responseStatus = -1;
        $responseType = '';
        $responseBody = '';

        switch ($headers['Content-Type']) {
            case 'application/json':
                $query = json_encode($params);
                break;

            case 'multipart/form-data':
                $query = $this->flatten($params);
                break;

            default:
                $query = http_build_query($params);
                break;
        }

        foreach ($headers as $i => $header) {
            $headers[] = $i . ':' . $header;
            unset($headers[$i]);
        }

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, php_uname('s') . '-' . php_uname('r') . ':php-' . phpversion());
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($curl, $header) use (&$responseHeaders) {
            $len = strlen($header);
            $header = explode(':', strtolower($header), 2);

            if (count($header) < 2) { // ignore invalid headers
                return $len;
            }

            $responseHeaders[strtolower(trim($header[0]))] = trim($header[1]);

            return $len;
        });

        if ($method != self::METHOD_GET) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Allow self signed certificates
        // if ($this->selfSigned) {
        //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // }

        $responseBody = curl_exec($ch);
        $responseType = (isset($responseHeaders['content-type'])) ? $responseHeaders['content-type'] : '';
        $responseStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        switch (substr($responseType, 0, strpos($responseType, ';'))) {
            case 'application/json':
                $responseBody = json_decode($responseBody, true);
            break;
        }

        if ((curl_errno($ch)/* || 200 != $responseStatus*/)) {
            throw new Exception(curl_error($ch) . ' with status code ' . $responseStatus, $responseStatus);
        }

        curl_close($ch);

        return $responseBody;
    }

    /**
     * Flatten params array to PHP multiple format.
     *
     * @param array $data
     * @param string $prefix
     *
     * @return array
     */
    protected function flatten(array $data, $prefix = '')
    {
        $output = [];

        foreach ($data as $key => $value) {
            $finalKey = $prefix ? "{$prefix}[{$key}]" : $key;

            if (is_array($value)) {
                $output += $this->flatten($value, $finalKey); // @todo: handle name collision here if needed
            } else {
                $output[$finalKey] = $value;
            }
        }

        return $output;
    }
}
