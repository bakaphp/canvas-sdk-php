<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Passthroughs;

use CURLFile;
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
        if ($this->request->hasHeader('Authorization')) {
            Resources::getClient()->setAuthToken($this->request->getHeader('Authorization'));
        }

        if ($this->request->hasFiles()) {
            $this->apiHeaders['Content-Type'] = 'multipart/form-data';
        }
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
        $uri = str_replace('/v1/', '', $this->router->getRewriteUri());
        $method = $this->request->getMethod();

        $response = Resources::getClient()->call($method, $uri, $this->apiHeaders, $this->getData());

        return $this->response($response);
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
                    $data = empty($this->request->getPost()) ? json_decode($this->request->getRawBody(), true) : $this->request->getPost();
                    return is_null($data) ? [] : $data;
                } else {
                    return $this->parseFileUpload($this->request->getPost());
                }
                break;

            case 'PUT':
                if (!$this->request->hasFiles()) {
                    $data = empty($this->request->getPut()) ? json_decode($this->request->getRawBody(), true) : $this->request->getPut();
                    return is_null($data) ? [] : $data;
                } else {
                    return $this->parseFileUpload($this->request->getPut());
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

        if ($this->request->hasFiles()) {
            foreach ($this->request->getUploadedFiles() as $file) {
                $cfile = new CURLFile($file->getTempName(), $file->getType(), $file->getName());
                $files[] = [
                    'file' => $cfile
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
