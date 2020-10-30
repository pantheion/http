<?php 

namespace Pantheion\Http\Client;

use GuzzleHttp\Client as GuzzleClient;
use Pantheion\Facade\Arr;
use Pantheion\Facade\Str;

/**
 * Wrapper around Guzzle's HTTP Client
 */
class Client
{
    /**
     * Guzzle's Client instance
     *
     * @var GuzzleClient
     */
    protected $client;

    /**
     * Client's headers
     *
     * @var array
     */
    protected $headers;

    /**
     * Client constructor function
     */
    public function __construct()
    {
        $this->client = new GuzzleClient();
        $this->headers = [];
    }

    /**
     * Handles all the requests so they
     * return a Response instance
     *
     * @param string $method
     * @param string $url
     * @param array $options
     * @return Response
     */
    protected function handle(string $method, string $url, array $options = [])
    {
        if(!Arr::empty($this->headers)) {
            $headers = Arr::merge(
                isset($options['headers']) ? $options['headers'] : [],
                $this->headers
            );

            $options['headers'] = $headers;
        }
        
        return new Response(
            $this->client->request($method, $url, $options)
        );
    }

    /**
     * Performs a GET request
     *
     * @param string $url
     * @param array $options
     * @return Response
     */
    public function get(string $url, array $query = [], array $options = [])
    {
        $options['query'] = $query;
        return $this->handle('GET', $url, $options);
    }

    /**
     * Performs a POST request
     *
     * @param string $url
     * @param array $data
     * @param array $options
     * @return Response
     */
    public function post(string $url = null, array $data = [], array $options = [])
    {
        if(!isset($options['headers']['Content-Type'])) {
            $options['headers'] = ['Content-Type' => 'application/x-www-form-urlencoded'];
        }

        if($options['headers']['Content-Type'] === 'multipart/form-data') {
            $options['multipart'] = [];
            foreach($data as $key => $value) {
                $options['multipart'] = ['name' => $key, 'contents' => $value];
            }
        } else {
            $options['form_params'] = $data;
        }

        return $this->handle('POST', $url, $options);
    }

    /**
     * Performs a PUT request
     *
     * @param string $url
     * @param array $data
     * @param array $options
     * @return Response
     */
    public function put(string $url = null, array $data = [], array $options = [])
    {
        if (!isset($options['headers']['Content-Type'])) {
            $options['headers'] = ['Content-Type' => 'application/x-www-form-urlencoded'];
        }

        if ($options['headers']['Content-Type'] === 'multipart/form-data') {
            $options['multipart'] = [];
            foreach ($data as $key => $value) {
                $options['multipart'] = ['name' => $key, 'contents' => $value];
            }
        } else {
            $options['form_params'] = $data;
        }

        return $this->handle('PUT', $url, $options);
    }

    /**
     * Performs a PATCH request
     *
     * @param string $url
     * @param array $data
     * @param array $options
     * @return Response
     */
    public function patch(string $url = null, array $data = [], array $options = [])
    {
        if (!isset($options['headers']['Content-Type'])) {
            $options['headers'] = ['Content-Type' => 'application/x-www-form-urlencoded'];
        }

        if ($options['headers']['Content-Type'] === 'multipart/form-data') {
            $options['multipart'] = [];
            foreach ($data as $key => $value) {
                $options['multipart'] = ['name' => $key, 'contents' => $value];
            }
        } else {
            $options['form_params'] = $data;
        }

        return $this->handle('PATCH', $url, $options);
    }

    /**
     * Performs a DELETE request
     *
     * @param string $url
     * @return Response
     */
    public function delete(string $url = null, array $options = [])
    {
        return $this->handle('DELETE', $url, $options);
    }

    /**
     * Adds headers to the Client
     *
     * @param string $key
     * @param string $value
     * @return Client
     */
    public function withHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Adds an Authorization Bearer header
     *
     * @param string $token
     * @return Client
     */
    public function withToken(string $token)
    {
        $this->headers['Authorization'] = "Bearer {$token}";
        return $this;
    }

    /**
     * Adds an Authorization Bearer header
     *
     * @param string $token
     * @return Client
     */
    public function withMultipart(string $input, $contents)
    {
        $this->headers['Content-Type'] = 'multipart/form-data';
        return $this;
    }
}