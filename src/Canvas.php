<?php

declare(strict_types=1);

namespace Canvas;

use Canvas\Util\LoggerInterface;

/**
 * Canvas class
*/
class Canvas
{
    // @var string The kanvas API key to be used for requests.
    public static $apiKey;
    // @var string The kanvas client_id to be used for Connect requests.
    public static $clientId;
    // @var string The base URL for the kanvas API.
    public static $apiBase = 'https://apidev.kanvas.dev';
    // @var string The base URL for the OAuth API.
    public static $connectBase = 'https://apidev.kanvas.dev';
    // @var string The base URL for the kanvas API uploads endpoint.
    public static $apiUploadBase = 'https://apidev.kanvas.dev';
    // @var string|null The version of the kanvas API to use for requests.
    public static $apiVersion = null;
    // @var string|null The account ID for connected accounts requests.
    public static $accountId = null;
    // @var string Path to the CA bundle used to verify SSL certificates
    public static $caBundlePath = null;
    // @var boolean Defaults to true.
    public static $verifySslCerts = true;

    /**
     * Logger library
     *
     * @var DefaultLogger
     */
    public static $logger = null;

    public static $authToken;

    const VERSION = '0.1.0';

    /**
     * @return string The API key used for requests.
     */
    public static function getApiKey(): string
    {
        return self::$apiKey;
    }

    /**
     * @return string The client_id used for Connect requests.
     */
    public static function getClientId(): string
    {
        return self::$clientId;
    }

    /**
     * @return Util\LoggerInterface The logger to which the library will
     *   produce messages.
     */
    public static function getLogger(): LoggerInterface
    {
        if (self::$logger == null) {
            return new Util\DefaultLogger();
        }
        return self::$logger;
    }

    /**
     * @param Util\LoggerInterface $logger The logger to which the library
     *   will produce messages.
     * @return void
     */
    public static function setLogger(LoggerInterface $logger): void
    {
        self::$logger = $logger;
    }

    /**
     * Sets the API key to be used for requests.
     *
     * @param string $apiKey
     * @return void
     */
    public static function setApiKey(string $apiKey): void
    {
        self::$apiKey = $apiKey;
    }

    /**
     * Sets the client_id to be used for Connect requests.
     *
     * @param string $clientId
     * @return void
     */
    public static function setClientId(string $clientId): void
    {
        self::$clientId = $clientId;
    }

    /**
     * @return string The API version used for requests. null if we're using the
     *    latest version.
     */
    public static function getApiVersion(): ?string
    {
        return self::$apiVersion;
    }

    /**
     * @param string $apiVersion The API version to use for requests.
     * @return void
     */
    public static function setApiVersion(string $apiVersion): void
    {
        self::$apiVersion = $apiVersion;
    }

    /**
     * @param string $caBundlePath
     * @return void
     */
    public static function setCABundlePath(string $caBundlePath): void
    {
        self::$caBundlePath = $caBundlePath;
    }

    /**
     * @return boolean
     */
    public static function getVerifySslCerts(): bool
    {
        return self::$verifySslCerts;
    }

    /**
     * @param boolean $verify
     * @return void
     */
    public static function setVerifySslCerts($verify): void
    {
        self::$verifySslCerts = $verify;
    }

    /**
     * @return array | null The application's information
     */
    public static function getAppInfo(): ?array
    {
        return self::$appInfo;
    }

    /**
     * Set authToken.
     * @param string $token
     * @return void
     */
    public static function setAuthToken($token): void
    {
        self::$authToken = $token;
    }

    /**
     * Get authToken.
     * @return string
     */
    public static function getAuthToken(): ?string
    {
        return self::$authToken;
    }

    /**
     * Set app info.
     *
     * @param string $appName The application's name
     * @param string $appVersion The application's version
     * @param string $appUrl The application's URL
     * @return void
     */
    public static function setAppInfo($appName, $appVersion = null, $appUrl = null, $appPartnerId = null): void
    {
        self::$appInfo = self::$appInfo ?: [];
        self::$appInfo['name'] = $appName;
        self::$appInfo['partner_id'] = $appPartnerId;
        self::$appInfo['url'] = $appUrl;
        self::$appInfo['version'] = $appVersion;
    }
}
