<?php

namespace Pantheion\Http;

use Pantheion\Http\Bag\DataBag;
use Pantheion\Http\Bag\CookieBag;
use Pantheion\Http\Bag\FileBag;

class Request
{
    protected function __construct($query, $data, $cookies, $files, $server)
    {
        $this->resolveRequest(
            $query, 
            $data, 
            $cookies, 
            $files, 
            $server
        );
    }

    protected function resolveRequest($query, $data, $cookies, $files, $server)
    {
        $this->query = new DataBag($query);
        $this->data = new DataBag($data);
        $this->cookies = new CookieBag($cookies);
        $this->files = new FileBag($files);
        $this->headers = new DataBag(getallheaders());
        
        $this->resolveServer($server);
    }

    protected function resolveServer($server)
    {
        $this->path = parse_url($server["REQUEST_URI"])["path"];
        $this->method = $server["REQUEST_METHOD"];
        $this->referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    }

    public static function capture()
    {
        return new Request(
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES,
            $_SERVER
        );
    }

    public function upload(string $name, string $folder = null)
    {
        if ($this->files->empty()) return 0;
        if (!$this->files->has($name)) return 0;

        return $this->files->get($name)->upload($folder);
    }

    public function uploadAll(string $folder = null)
    {
        if($this->files->empty()) return 0;

        $files = [];
        foreach($this->files as $key => $file)
        {
            $files[$key] = $file->upload($folder);
        }        

        return $files;
    }
    
    public function validate()
    {
        # code...
    }

    public function resolveUrl()
    {
        return array_values(array_filter(explode("/", $this->path), function ($value) {
            return $value !== "";
        }));
    }
}