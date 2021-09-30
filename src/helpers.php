<?php

if (!function_exists('file_uploader')) {
    /**
     * Get the FileUploader instance
     *
     * @return \HexideDigital\FileUploader\Classes\FileUploader
     */
    function file_uploader(): \HexideDigital\FileUploader\Classes\FileUploader
    {
        return app(\HexideDigital\FileUploader\Classes\FileUploader::class);
    }
}

if (!function_exists('fu_disk')) {
    /**
     * Get the FileUploader instance with setup disk
     *
     * @param string $disk
     * @return \HexideDigital\FileUploader\Classes\FileUploader
     */
    function fu_url(string $disk): \HexideDigital\FileUploader\Classes\FileUploader
    {
        return file_uploader()->disk($disk);
    }
}

if (!function_exists('fu_url')) {
    /**
     * Get url to file in public storage
     *
     * @param string|null $path
     * @return string
     */
    function fu_url(?string $path): string
    {
        return file_uploader()->url($path);
    }
}


