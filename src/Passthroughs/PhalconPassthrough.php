<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Passthroughs;

use GuzzleHttp\Client;
use Kanvas\Sdk\Kanvas;
use Kanvas\Sdk\Resources;
use Phalcon\Http\Response;

/**
 * Trait ResponseTrait.
 *
 * @package Canvas\Traits
 *
 * @property Users $user
 * @property Config $config
 * @property Request $request
 * @property Auth $auth
 * @property \Phalcon\Di $di
 *
 */
trait PhalconPassthrough
{
    /**
     * @string
     */
    public $apiHeaders = [];

    /**
     * Construct setup setting.
     *
     * @todo Fix the Content Type Header issue when sending files. Need to stablish boundaries for multipart/form_data  files
     *
     * @return void
     */
    public function onConstruct()
    {
        $this->setApiHeaders([
            'Authorization' => $this->request->hasHeader('Authorization') ? $this->request->getHeader('Authorization') : '',
        ]);
    }

    /**
     * Function to set the API headers since the property is protected.
     *
     * @param mixed $version
     *
     * @return void
     */
    public function setApiHeaders($apiHeaders) : void
    {
        $this->apiHeaders = $apiHeaders;
    }

    /**
     * Get request options to send.
     *
     * @return array
     */
    public function getRequestData(string $method, array $data) : array
    {
        return [
            'headers' => $this->apiHeaders,
            key($data) => $data[key($data)]
        ];
    }

    /**
     * Function tasked with delegating API requests to the configured API.
     *
     * @todo Verify headers being received from the API response before returning the request response.
     *
     * @return \Phalcon\Http\Response
     */
    public function transporter() : Response
    {
        // Get all router params
        $routeParams = $this->router->getParams();

        $uri = str_replace('/v1/', '', $this->router->getRewriteUri());
        $method = $this->request->getMethod();

        if ($this->request->hasHeader('Authorization')) {
            Resources::getClient()->setAuthToken($this->request->getHeader('Authorization'));
        }

        $response = Resources::getClient()->call($method, $uri, [], $this->getData());

        return $this->response($response);
    }

    /**
     * Function that executes the request to the configured API.
     *
     * @param string $method - The request method
     * @param string $url - The request URL
     * @param array $data - The form data
     *
     * @return JSON
     */
    public function makeRequest($url, $method = 'GET', $data = [])
    {
        $client = new Client();

        $parse = function ($error) {
            if ($error->hasResponse()) {
                return $error->getResponse();
            }

            return json_decode($error->getMessage());
        };

        try {
            $response = $client->request($method, $url, $this->getRequestData($method, $data));
            return $response;
        } catch (\GuzzleHttp\Exception\BadResponseException $error) {
            return $parse($error);
        } catch (\GuzzleHttp\Exception\ClientException $error) {
            return $parse($error);
        } catch (\GuzzleHttp\Exception\ConnectException $error) {
            return $parse($error);
        } catch (\GuzzleHttp\Exception\RequestException $error) {
            return $parse($error);
        }
    }

    /**
     * Function that obtains the data as per the request type.
     *
     * @return array
     */
    public function getData() : array
    {
        switch ($this->request->getMethod()) {
            case 'GET':
                $queryParams = $this->request->getQuery();
                unset($queryParams['_url']);
                return $queryParams;
                break;

            case 'POST':
                if (!$this->request->hasFiles()) {
                    return empty($this->request->getPost()) ? json_decode($this->request->getRawBody(), true) : $this->request->getPost();
                } else {
                    return $this->parseFileUpload($this->request->getPost());
                }
                break;

            case 'PUT':
                if (!$uploads) {
                    return empty($this->request->getPost()) ? json_decode($this->request->getRawBody(), true) : $this->request->getPut();
                } else {
                    $this->parseFileUpload($this->request->getPut());
                    return ['multipart' => $uploads];
                }

                break;
            default:
                return [];
                break;
        }
    }

    /**
     * Parse incoming file data.
     *
     * @return array
     */
    private function parseFileUpload($request) : array
    {
        $files = [];

        // foreach ($request as $key => $value) {
        //     $files[$key] = $value;
        // }

        if ($this->request->hasFiles()) {
            foreach ($this->request->getUploadedFiles() as $file) {
                $files[] = [
                    'name' => 'file',
                    'contents' => file_get_contents($file->getTempName()),
                    'filename' => $file->getName()
                ];
            }
        }

        return $files;
    }

    /**
     * Get the record by its primary key.
     *
     * @param mixed $id
     *
     * @throws Exception
     *
     * @return Response
     */
    public function getById($id) : Response
    {
        return $this->transporter();
    }
}
