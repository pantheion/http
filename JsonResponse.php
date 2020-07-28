<?php

namespace Pantheion\Http;

class JsonResponse extends Response
{
    public function __construct($content)
    {
        $json = json_encode($content, JSON_UNESCAPED_UNICODE);
        parent::__construct($json);

        $this->header("Cache-Control", "max-age=3600, public")
            ->header("Content-Type", "application/json");
    }
}
