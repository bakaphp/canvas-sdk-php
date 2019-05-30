<?php

namespace Canvas\Util;

use Canvas\Exception;


class RequestOptions
{
    /**
     * @var array A list of headers that should be persisted across requests.
     */
    public static $HEADERS_TO_PERSIST = [];

    public $headers;

    public $apiKey;

    public $apiBase;

    public function __construct($key = null, $headers = [], $base = null)
    {
        $this->apiKey = $key;
        $this->headers = $headers;
        $this->apiBase = $base;
    }

    /**
     * Unpacks an options array and merges it into the existing RequestOptions
     * object.
     * @param array|string|null $options a key => value array
     *
     * @return RequestOptions
     */
    public function merge($options)
    {
        $other_options = self::parse($options);

        if ($other_options->apiKey === null) {
            $other_options->apiKey = $this->apiKey;
        }

        if ($other_options->apiBase === null) {
            $other_options->apiBase = $this->apiBase;
        }

        $other_options->headers = array_merge($this->headers, $other_options->headers);

        return $other_options;
    }

    /**
     * Discards all headers that we don't want to persist across requests.
     */
    public function discardNonPersistentHeaders()
    {
        foreach ($this->headers as $headerName => $headerValue) {
            if (!in_array($headerName, self::$HEADERS_TO_PERSIST)) {
                unset($this->headers[$headerName]);
            }
        }
    }

    /**
     * Unpacks an options array into an RequestOptions object
     * @param array|string|null $options a key => value array
     *
     * @return RequestOptions
     */
    public static function parse($options)
    {
        if ($options instanceof self) {
            return $options;
        } elseif (is_null($options)) {
            return new RequestOptions(null, [], null);
        } elseif (is_string($options)) {
            return new RequestOptions($options, [], null);
        } elseif (is_array($options)) {
            $headers = [];
            $key = null;
            $base = null;
            if (array_key_exists('api_key', $options)) {
                $key = $options['api_key'];
            }

            if (array_key_exists('api_base', $options)) {
                $base = $options['api_base'];
            }
            return new RequestOptions($key, $headers, $base);
        }

        $message = 'The second argument to Canvas API method calls is an '
           . 'optional per-request apiKey, which must be a string, or '
           . 'per-request options, which must be an array.';
        throw new Exception\Api($message);
    }
}
