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

    public function __construct($key = null, $headers = [], $base = null, $query = null)
    {
        $this->apiKey = $key;
        $this->headers = $headers;
        $this->apiBase = $base;
        $this->query = $query;
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
     * Unpacks an options array into an RequestOptions object.
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
            $query = '?';
            if (array_key_exists('api_key', $options)) {
                $key = $options['api_key'];
            }

            if (array_key_exists('api_base', $options)) {
                $base = $options['api_base'];
            }

            if (array_key_exists('conditions', $options)) {
                $query .= self::parseConditions($options['conditions']);
            }

            if (array_key_exists('relationships', $options)) {
                $query .= self::parseRelationships($options['relationships']);
            }

            if (array_key_exists('custom_conditions', $options)) {
                $query .= self::parseCustomConditions($options['custom_conditions']);
            }

            if (array_key_exists('relationships_conditions', $options)) {
                $query .= 'rq=';
                foreach ($options['relationships_conditions'] as $condition) {
                    $query .= $condition == end($options['relationships_conditions']) ? $condition : $condition . ',';
                }
                $query .= '&';
            }
            return new RequestOptions($key, $headers, $base, $query);
        }

        $message = 'The second argument to Canvas API method calls is an '
           . 'optional per-request apiKey, which must be a string, or '
           . 'per-request options, which must be an array.';
        throw new Exception\Api($message);
    }

    /**
     * Parse relationships Params
     * @param array $relationships
     * @return string
     */
    private function parseRelationships(array $relationships): string
    {
        $query = '';
        $query .= 'relationships=';
        foreach ($relationships as $relationship) {
            $query .= $relationship == end($relationships) ? $relationship : $relationship . ',';
        }
        $query .= '&';

        return $query;
    }

    /**
     * Parse conditions Params
     * @param array $relationships
     * @return string
     */
    private function parseConditions(array $conditions): string
    {
        $query = '';
        $query .= 'q=(';
        foreach ($conditions as $condition) {
            $query .= $condition == end($conditions) ? $condition : $condition . ',';
        }
        $query .= ')&';

        return $query;
    }

    /**
     * Parse custom conditions Params
     * @param array $relationships
     * @return string
     */
    private function parseCustomConditions(array $conditions): string
    {
        $query = '';
        $query .= 'cq=(';
        foreach ($conditions as $condition) {
            $query .= $condition == end($conditions) ? $condition : $condition . ',';
        }
        $query .= ')&';

        return $query;
    }
}
