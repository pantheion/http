<?php

namespace Pantheion\Http;

use Pantheion\Filesystem\Directory;

class File
{
    const FILES_FOLDER = "public" . DIRECTORY_SEPARATOR . "files";
    
    public function __construct($name, $type, $tmpName, $error, $size)
    {
        $this->name = $name;
        $this->type = $type;
        $this->tmpName = $tmpName;
        $this->error = $error;
        $this->size = $size;
        $this->extension = pathinfo($this->name, PATHINFO_EXTENSION);
    }

    public function upload(string $folder = null)
    {
        $destination = File::FILES_FOLDER;

        if(!is_null($folder)) 
        {
            $subFolder = File::FILES_FOLDER . DIRECTORY_SEPARATOR . $folder;
            if(!Directory::exists($subFolder))
            {
                Directory::create($subFolder);
            }

            $destination = $subFolder;
        }

        if(!$this->canUpload($destination)) return 0;

        $path = $destination . DIRECTORY_SEPARATOR . $this->randomName() . "." . $this->extension;
        move_uploaded_file($this->tmpName, $path);

        return ltrim($path, "public" . DIRECTORY_SEPARATOR);
    }

    protected function canUpload($destination)
    {
        $isWritable = is_writable($destination);

        return $isWritable === false || $this->error >= 1 ? false : true;
    }

    protected function randomName()
    {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < 32; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }
}