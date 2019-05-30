<?php

namespace Canvas\Exception;

use Exception;

abstract class Base extends Exception
{
    public function __construct(
        $message,
        $httpStatus = null,
        $httpBody = null,
        $jsonBody = null,
        $httpHeaders = null
    ) {
        parent::__construct($message);
        $this->httpStatus = $httpStatus;
        $this->httpBody = $httpBody;
        $this->jsonBody = $jsonBody;
        $this->httpHeaders = $httpHeaders;
    }

    public function getStripeCode()
    {
        return $this->stripeCode;
    }

    public function getHttpStatus()
    {
        return $this->httpStatus;
    }

    public function getHttpBody()
    {
        return $this->httpBody;
    }

    public function getJsonBody()
    {
        return $this->jsonBody;
    }

    public function getHttpHeaders()
    {
        return $this->httpHeaders;
    }
}
