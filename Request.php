<?php

namespace Pantheion\Http;

use Pantheion\Http\Bag\DataBag;
use Pantheion\Http\Bag\CookieBag;
use Pantheion\Http\Bag\FileBag;
use Pantheion\Session\Session;

class Request
{
    /**
     * Query data
     *
     * @var DataBag
     */
    public $query;

    /**
     * Form data
     *
     * @var DataBag
     */
    public $data;

    /**
     * Request cookies
     *
     * @var CookieBag
     */
    public $cookies;

    /**
     * Uploaded files
     *
     * @var FileBag
     */
    public $files;

    /**
     * Request headers
     *
     * @var DataBag
     */
    public $headers;

    /**
     * Request path
     *
     * @var string
     */
    public $path;

    /**
     * Request method
     *
     * @var string
     */
    public $method;

    /**
     * Request referer
     *
     * @var string|null
     */
    public $referer;

    protected function __construct(array $query, array $data, array $cookies, array $files, array $server)
    {
        $this->resolveRequest(
            $query, 
            $data, 
            $cookies, 
            $files, 
            $server
        );
    }

    protected function resolveRequest(array $query, array $data, array $cookies, array $files, array $server)
    {
        $this->query = new DataBag($query);
        $this->data = new DataBag($data);
        $this->cookies = new CookieBag($cookies);
        $this->files = new FileBag($files);
        $this->headers = new DataBag(getallheaders());
        
        $this->resolveServer($server);
        try {
            $this->session = app(Session::class);
        } catch(\Exception $e) {
            $this->session = null;
        }
    }

    protected function resolveServer(array $server)
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

    /**
     * Returns the current session
     *
     * @return Session
     */
    public function session()
    {
        return $this->session;
    }

    /**
     * Flashes all the input data
     *
     * @return void
     */
    public function flash()
    {
        return $this->session()->flashInput($this->data->all());
    }

    /**
     * Chooses a certain values from
     * the array
     *
     * @param array $keys
     * @return array
     */
    protected function only(array $keys) 
    {
        $input = [];
        foreach($keys as $key) {
            if($this->data->has($key)) {
                $input[$key] = $this->data->get($key);
            }
        }
        
        return $input;
    }

    /**
     * Flashes only certain inputs
     * from the request
     *
     * @param array|string $keys
     * @return void
     */
    public function flashOnly($keys) 
    {
        if(!is_array($keys)) {
            $keys = [$keys];
        }

        return $this->session()->flashInput($this->only($keys));
    }

    /**
     * Returns only the keys that
     * are not listed in the parameters
     *
     * @param array $keys
     * @return array
     */
    protected function except(array $keys) 
    {
        return array_filter($this->data->all(), function($value, $key) use ($keys) {
            return !in_array($key, $keys);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Flashes the input data except
     * the ones specified in the
     * parameters
     *
     * @param string|mixed $keys
     * @return void
     */
    public function flashExcept($keys) 
    {
        if (!is_array($keys)) {
            $keys = [$keys];
        }

        return $this->session()->flashInput($this->except($keys));
    }
}