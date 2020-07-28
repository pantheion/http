<?php

namespace Pantheion\Http;

class Response
{
    public function __construct($content, $status = 200)
    {
        $this->content = $content;
        $this->status = $status;
        $this->cookies = [];
        $this->headers = [];
    }

    public function send()
    {
        $this->sendCookies();
        $this->sendHeaders();
        $this->sendStatus();
        $this->sendContent();
    }

    public function cookie(Cookie $cookie)
    {
        $this->cookies[$cookie->key] = $cookie;
    }

    public function header($key, $value)
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function status(int $status)
    {
        $this->status = $status;
        return $this;
    }

    public function sendCookies()
    {
        foreach($this->cookies as $cookie)
        {
            header($cookie->toHeader());
        }
    }

    public function sendHeaders()
    {
        $header = "%s:%s";

        foreach ($this->headers as $key => $value) 
        {
            header(sprintf($header, $key, $value), true, $this->status);
        }
    }

    public function sendStatus()
    {
        http_response_code($this->status);
    }

    public function sendContent()
    {
        echo $this->content;
    }
}
