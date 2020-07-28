<?php

namespace Pantheion\Http;

class Cookie
{
    public function __construct(
        $key, 
        $value,
        $expires = 0,
        string $path = "",
        string $domain = "",
        bool $secure = false,
        bool $httpOnly = false
    ) {
        $this->key = $key;
        $this->value = $value;
        $this->expires = $expires;
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
    }

    public function toHeader()
    {
        $header = "Set-Cookie: %s=%s; Expires=%s; %s %s %s %s";

        $expires = $this->expires ? (new \DateTime("now", new \DateTimeZone('Europe/London')))
                                        ->setTimestamp(time() + $this->expires)
                                        ->format("D, d M Y H:i:s") 
                                    : strval($this->expires);

        $path = $this->path ? sprintf("Path=%s;", $this->path) : "";
        $domain = $this->domain ? sprintf("Domain=%s;", $this->domain) : "";
        $secure = $this->secure ? "Secure;" : "";
        $httpOnly = $this->httpOnly ? "HttpOnly;" : "";

        return rtrim(sprintf(
            $header,
            $this->key,
            $this->value,
            $expires,
            $path,
            $domain,
            $secure,
            $httpOnly
        ));
    }
}