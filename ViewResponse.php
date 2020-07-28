<?php

namespace Pantheion\Http;

class ViewResponse extends Response
{
    public function __construct($content)
    {
        parent::__construct($content);

        $this->header("Cache-Control", "max-age=3600, public")
            ->header("Content-Type", "text/html");
    }
}
