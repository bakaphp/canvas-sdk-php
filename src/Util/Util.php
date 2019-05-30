<?php

namespace Canvas\Util;

use Canvas\Util\CanvasObject;

abstract class Util
{
    private static $isMbstringAvailable = null;

    /**
     * Converts a response from the Canvas API to the corresponding PHP object.
     *
     * @param array $response The response from the Canvas API.
     * @param array $options
     * @return CanvasObject|array
     */
    public static function convertToCanvasObject($response, $options)
    {
        $types = [
            //Common data structures

            //Business objects

        ];

        if(self::isList($response)) {
            $mapped = [];
            foreach($response as $object) {
                $mapped[] = self::convertToCanvasObject($object, $options);
            }
            return $mapped;
        } elseif(is_array($response)){
            if (isset($response['object']) && is_string($response['object']) && isset($types[$response['object']])) {
                $class = $types[$response['object']];
            } else {
                $class = CanvasObject::class;
            }
            return $class::constructFrom($response, $options);
        } else {
            return $response;
        }
    }

    /**
     * @param string|mixed $value A string to UTF8-encode.
     *
     * @return string|mixed The UTF8-encoded string, or the object passed in if
     *    it wasn't a string.
     */
    public static function utf8($value)
    {
        if (self::$isMbstringAvailable === null) {
            self::$isMbstringAvailable = function_exists('mb_detect_encoding');
        }
        if (is_string($value) && self::$isMbstringAvailable && mb_detect_encoding($value, "UTF-8", true) != "UTF-8") {
            return utf8_encode($value);
        } else {
            return $value;
        }
    }

}
