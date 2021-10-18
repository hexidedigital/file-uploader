<?php

namespace HexideDigital\FileUploader\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class FileUploader
 * @package HexideDigital\FileUploader\Facades
 */
class FileUploader extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'file_uploader';
    }
}
