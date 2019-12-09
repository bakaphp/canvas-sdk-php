<?php

namespace Kanvas\Sdk\Exception;

if (interface_exists(\Throwable::class)) {
    /**
     * The base interface for all Stripe exceptions.
     *
     * @package Stripe\Exception
     */
    interface ExceptionInterface extends \Throwable
    {
    }
} else {
    /**
     * The base interface for all Stripe exceptions.
     *
     * @package Stripe\Exception
     */
    // phpcs:disable PSR1.Classes.ClassDeclaration.MultipleClasses
    interface ExceptionInterface
    {
    }
    // phpcs:enable
}
