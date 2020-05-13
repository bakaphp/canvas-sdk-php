<?php

declare(strict_types=1);

namespace Kanvas\Sdk;

use function Kanvas\Sdk\Core\envValue;
use Kanvas\Sdk\Util\LoggerInterface;

/**
 * Canvas class.
 */
class Kanvas
{
    // @var string The kanvas API key to be used for requests.
    public static $apiKey;
    // @var string The kanvas client_id to be used for Connect requests.
    public static $clientId;
    // @var string The kanvas client_secret_id to be used for Connect requests.
    public static $clientSecretId;
    // @var string The base URL for the kanvas API.
    public static $apiBase = null;
    // @var string The base URL for the OAuth API.
    public static $connectBase = null;
    // @var string The base URL for the kanvas API uploads endpoint.
    public static $apiUploadBase = null;
    // @var string|null The version of the kanvas API to use for requests.
    public static $apiVersion = null;
    // @var string|null The account ID for connected accounts requests.
    public static $accountId = null;
    // @var string Path to the CA bundle used to verify SSL certificates
    public static $caBundlePath = null;
    // @var boolean Defaults to true.
    public static $verifySslCerts = true;

    /**
     * Logger library.
     *
     * @var DefaultLogger
     */
    public static $logger = null;

    public static $authToken;

    const VERSION = '0.1.0';

    /**
     * Get Kanvas API URL.
     *
     * @return string
     */
    public function setKanvasApiUrl() : void
    {
        self::$apiBase = envValue('KANVAS_API_URL', 'https://api.kanvas.dev');
        self::$connectBase = envValue('KANVAS_API_URL', 'https://api.kanvas.dev');
        self::$apiUploadBase = envValue('KANVAS_API_URL', 'https://api.kanvas.dev');
    }

    /**
     * @return string The API key used for requests.
     */
    public static function getApiKey() : string
    {
        return self::$apiKey;
    }

    /**
     * @return string The client_id used for Connect requests.
     */
    public static function getClientId() : string
    {
        return self::$clientId;
    }

    /**
     * @return string The client_secret_id used for Connect requests.
     */
    public static function getClientSecretId() : string
    {
        return self::$clientSecretId;
    }

    /**
     * @return Util\LoggerInterface The logger to which the library will
     *   produce messages.
     */
    public static function getLogger() : LoggerInterface
    {
        if (self::$logger == null) {
            return new Util\DefaultLogger();
        }
        return self::$logger;
    }

    /**
     * @param Util\LoggerInterface $logger The logger to which the library
     *   will produce messages.
     *
     * @return void
     */
    public static function setLogger(LoggerInterface $logger) : void
    {
        self::$logger = $logger;
    }

    /**
     * Sets the API key to be used for requests.
     *
     * @param string $apiKey
     *
     * @return void
     */
    public static function setApiKey(string $apiKey) : void
    {
        self::setKanvasApiUrl();
        self::$apiKey = $apiKey;
    }

    /**
     * Sets the client_id to be used for Connect requests.
     *
     * @param string $clientId
     *
     * @return void
     */
    public static function setClientId(string $clientId) : void
    {
        self::$clientId = $clientId;
    }

    /**
     * Sets the client_secret_id to be used for Connect requests.
     *
     * @param string $clientSecretId
     *
     * @return void
     */
    public static function setClienSecrettId(string $clientSecretId) : void
    {
        self::$clientSecretId = $clientSecretId;
    }

    /**
     * @return string The API version used for requests. null if we're using the
     *    latest version.
     */
    public static function getApiVersion() : ?string
    {
        return self::$apiVersion;
    }

    /**
     * @param string $apiVersion The API version to use for requests.
     *
     * @return void
     */
    public static function setApiVersion(string $apiVersion) : void
    {
        self::$apiVersion = $apiVersion;
    }

    /**
     * @param string $caBundlePath
     *
     * @return void
     */
    public static function setCABundlePath(string $caBundlePath) : void
    {
        self::$caBundlePath = $caBundlePath;
    }

    /**
     * @return boolean
     */
    public static function getVerifySslCerts() : bool
    {
        return self::$verifySslCerts;
    }

    /**
     * @param bool $verify
     *
     * @return void
     */
    public static function setVerifySslCerts($verify) : void
    {
        self::$verifySslCerts = $verify;
    }

    /**
     * @return array | null The application's information
     */
    public static function getAppInfo() : ?array
    {
        return self::$appInfo;
    }

    /**
     * Set authToken.
     *
     * @param string $token
     *
     * @return void
     */
    public static function setAuthToken($token) : void
    {
        self::$authToken = $token;
    }

    /**
     * Get authToken.
     *
     * @return string
     */
    public static function getAuthToken() : ?string
    {
        return self::$authToken;
    }

    /**
     * Set app info.
     *
     * @param string $appName The application's name
     * @param string $appVersion The application's version
     * @param string $appUrl The application's URL
     *
     * @return void
     */
    public static function setAppInfo($appName, $appVersion = null, $appUrl = null, $appPartnerId = null) : void
    {
        self::$appInfo = self::$appInfo ?: [];
        self::$appInfo['name'] = $appName;
        self::$appInfo['partner_id'] = $appPartnerId;
        self::$appInfo['url'] = $appUrl;
        self::$appInfo['version'] = $appVersion;
    }
}
