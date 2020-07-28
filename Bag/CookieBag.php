<?php

namespace Pantheion\Http\Bag;

use Pantheion\Http\Cookie;

class CookieBag extends DataBag
{
    public function __construct(array $cookies)
    {
        $this->resolveCookies($cookies);
    }

    protected function resolveCookies($cookies)
    {
        $this->data = [];

        foreach($cookies as $key => $value)
        {
            $this->data[$key] = new Cookie($key, $value);
        }
    }
}
