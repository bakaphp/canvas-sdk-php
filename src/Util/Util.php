<?php

namespace Kanvas\Sdk\Util;

abstract class Util
{
    private static $isMbstringAvailable = null;

    /**
     * Converts a response from the Canvas API to a simple PHP object.
     *
     * @param array $response The response from the Canvas API.
     * @param RequestOptions $opts
     * @param string $object
     *
     * @return object|object[]
     */
    public static function convertToObject(array $response, string $object = null)
    {
        // check whether the response is a multidimensional array or just an array. Treat each one accordingly.
        if (!array_key_exists(0, $response)) {
            $instance = new $object();
            foreach ($response as $key => $value) {
                $instance->$key = $value;
            }
            return $instance;
        }

        $objectsArray = [];
        foreach ($response as $element) {
            $instance = new $object();
            foreach ($element as $key => $value) {
                $instance->$key = $value;
            }
            $objectsArray[] = $instance;
        }

        return $objectsArray;
    }

    /**
     * @param string|mixed $value A string to UTF8-encode.
     *
     * @return string|mixed The UTF8-encoded string, or the object passed in if
     *    it wasn't a string.
     */
    public static function utf8(string $value) : ?string
    {
        if (self::$isMbstringAvailable === null) {
            self::$isMbstringAvailable = function_exists('mb_detect_encoding');
        }
        if (is_string($value) && self::$isMbstringAvailable && mb_detect_encoding($value, 'UTF-8', true) != 'UTF-8') {
            return utf8_encode($value);
        } else {
            return $value;
        }
    }

    /**
     * Given a ID return its as a array?
     *
     * @param mixed $id
     *
     * @return array
     */
    public static function normalizeId($id) : array
    {
        if (is_array($id)) {
            $params = $id;
            $id = $params['id'];
            unset($params['id']);
        } else {
            $params = [];
        }
        return [$id, $params];
    }

    /**
     * Returns UNIX timestamp in milliseconds.
     *
     * @return int current time in millis
     */
    public static function currentTimeMillis() : int
    {
        return (int) round(microtime(true) * 1000);
    }

    /**
     * Convert params from standard phalcon find to SDK standards.
     *
     * @param array $params
     *
     * @return array
     */
    public static function convertParams(array $params) : array
    {
        $searchBy = [];
        if (isset($params['conditions'])) {
            // Find for OR or AND statements and push them to an array
            // $conditions = str_replace('and',',', $params['conditions']);
            $conditions = preg_split("/\b(?:and|or)\b/", $params['conditions']);

            //If there is a bind among the params then we need to map the conditions wildcards to the elements on bind
            foreach ($conditions as $key => $value) {
                if (isset($params['bind']) && array_key_exists($key, $params['bind'])) {
                    $bindValue = $params['bind'][$key];
                    $conditions[$key] = !is_numeric($bindValue) ? str_replace(' ', '', str_replace('= ?' . $key, ':%' . $bindValue . '%', $value)) : str_replace(' ', '', str_replace('= ?' . $key, ':' . $bindValue, $value));
                } else {
                    $conditionArray = explode(' ', rtrim($value));
                    $conditions[$key] = !is_numeric(end($conditionArray)) ? str_replace(' ', '', str_replace('= ' . end($conditionArray), ':%' . end($conditionArray) . '%', $value)) : str_replace(' ', '', str_replace('= ' . end($conditionArray), ':' . end($conditionArray), $value));
                }
            }
            $searchBy['conditions'] = $conditions;
        }

        if (isset($params['order'])) {
            $params['order'] = strpos($params['order'], 'DESC') ? str_replace(' DESC', '|desc', $params['order']) : str_replace(' ASC', '|ASC', $params['order']);
            $searchBy['sort'] = $params['order'];
        }

        if (isset($params['limit'])) {
            $searchBy['limit'] = $params['limit'];
        }

        return $searchBy;
    }
}
