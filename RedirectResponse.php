<?php

namespace Pantheion\Http;

class RedirectResponse extends Response
{
    public function __construct($url)
    {
        $this->header("Cache-Control", "max-age=3600, public")
            ->header("Location", $url);
    }
}
