<?php

namespace Pantheion\Http\Client;

use Psr\Http\Message\ResponseInterface;
use Pantheion\Facade\Str;
use Pantheion\Facade\Arr;

/**
 * Wrapper around the Psr's Response
 */
class Response
{
    /**
     * Psr's Response instance
     *
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Client's Response constructor function
     *
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Returns the body as a JSON string
     *
     * @return string
     */
    public function body()
    {
        return $this->response->getBody()->getContents();
    }

    /**
     * Returns the body in a JSON decoded array
     *
     * @return array
     */
    public function json()
    {
        return json_decode($this->body(), true);
    }

    /**
     * Returns the response's status code
     *
     * @return int
     */
    public function status()
    {
        return $this->response->getStatusCode();
    }

    /**
     * Check is the response was successful
     *
     * @return bool
     */
    public function successful()
    {
        return Str::startsWith(strval($this->status()), '2') || Str::startsWith(strval($this->status()), '3');
    }

    /**
     * Checks if the response's status
     * code was OK
     *
     * @return bool
     */
    public function ok()
    {
        return $this->status() === 200;
    }

    /**
     * Checks if the response failed
     *
     * @return bool
     */
    public function failed()
    {
        return Str::startsWith(strval($this->status()), '4') || Str::startsWith(strval($this->status()), '5');
    }

    /**
     * Checks if the response has a client error
     *
     * @return bool
     */
    public function clientError()
    {
        return Str::startsWith(strval($this->status()), '4');
    }

    /**
     * Checks if the response has a server error
     *
     * @return bool
     */
    public function serverError()
    {
        return Str::startsWith(strval($this->status()), '5');
    }

    /**
     * Returns a header from the response
     *
     * @param string $key
     * @return string|null
     */
    public function header(string $key)
    {
        return $this->response->hasHeader($key) ? reset($this->response->getHeader($key)) : null;
    }

    /**
     * Returns all headers from the response
     *
     * @return array
     */
    public function headers()
    {
        return $this->response->getHeaders();
    }
}