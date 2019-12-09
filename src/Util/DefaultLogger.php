<?php

namespace Kanvas\Sdk\Util;

use Kanvas\Sdk\Exception\BadMethodCallException;

/**
 * A very basic implementation of LoggerInterface that has just enough
 * functionality that it can be the default for this library.
 */
class DefaultLogger implements LoggerInterface
{
    /**
     * PSR3 Standard Log function
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function error($message, array $context = [])
    {
        if (count($context) > 0) {
            throw new BadMethodCallException('DefaultLogger does not currently implement context. Please implement if you need it.');
        }
        error_log($message);
    }
}
