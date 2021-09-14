<?php

namespace HexideDigital\FileUploader;

use Illuminate\Support\ServiceProvider;
use HexideDigital\FileUploader\Classes\FileUploader;

/**
 * Class FileUploaderServiceProvider
 * @package HexideDigital\FileUploader
 */
class FileUploaderServiceProvider extends ServiceProvider
{

    /**
     * Boot the instance.
     *
     * @return void
     */
    public function boot(){
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('file_uploader', FileUploader::class);
    }

}
