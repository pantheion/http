<?php

namespace Pantheion\Http\Bag;

use Pantheion\Http\File;

class FileBag extends DataBag
{
    public function __construct(array $files)
    {
        $this->resolveFiles($files);
    }

    protected function resolveFiles($files)
    {
        $this->data = [];

        foreach ($files as $key => $file) {
            $this->data[$key] = new File(
                $file["name"],
                $file["type"],
                $file["tmp_name"],
                $file["error"],
                $file["size"]
            );
        }
    }
}
