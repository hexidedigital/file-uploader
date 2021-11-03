<?php

namespace HexideDigital\FileUploader\Classes;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


/**
 * Class FileUploader
 * @package HexideDigital\FileUploader\Classes
 */
class FileUploader
{
    /**
     * @var string
     */
    private $disk = 'public';

    /**
     * @param string $disk
     * @return $this
     */
    public function disk(string $disk): self
    {
        $this->disk = $disk;
        return $this;
    }

    /**
     * @param string|null $path
     * @return string
     */
    public function url(?string $path): string
    {
        if (empty($path)) return '';

        $path = $this->_clearPath($path);

        return Storage::disk($this->disk)->url($path);
    }

    /**
     * @param string|null $path
     * @return string
     */
    public function path(?string $path): string
    {
        if (empty($path)) return '';

        $path = $this->_clearPath($path);

        return Storage::disk($this->disk)->path($path);
    }

    /**
     * @param string|null $path
     * @return bool
     */
    public function exists(?string $path): bool
    {
        if (empty($path)) return false;
        $path = $this->_clearPath($path);
        return Storage::disk($this->disk)->exists($path);
    }

    /**
     * @param string|null $path
     * @return bool
     */
    public function delete(?string $path): bool
    {
        if (empty($path)) return false;
        $path = $this->_clearPath($path);
        return Storage::disk($this->disk)->delete($path);
    }

    /**
     * @param UploadedFile|null $file
     * @param string $type
     * @param string|null $module
     * @param string|null $uniq_id
     * @return string|null
     */
    public function put(?UploadedFile $file, string $type, ?string $module = null, ?string $uniq_id = null): ?string
    {
        if (empty($file)) {
            return null;
        }

        $module = empty($module) ? '' : '/' . $module;

        $path = $this->_preparePath($type . $module, $uniq_id);

        return Storage::disk($this->disk)->putFile($path, new File($file->getPathname())) ?? null;
    }

    /**
     * @param string $from
     * @param string $to
     * @return bool
     */
    public function move(string $from, string $to): bool
    {
        $from = $this->_clearPath($from);

        $to = $this->_prepareToMove($to);

        return Storage::disk($this->disk)->move($from, $to);
    }

    /**
     * @param string $root
     * @param string|null $uniq_id
     * @return string
     */
    private function _preparePath(string $root, ?string $uniq_id = null): string
    {
        $hash = md5($uniq_id ?? Str::random());

        $a = substr($hash, 0, 2);
        $b = substr($hash, 2, 2);

        return $root . '/' . $a . '/' . $b;
    }

    /**
     * @param string $link
     * @return string
     */
    private function _prepareToMove(string $link): string
    {
        $link = explode('/', $link);
        $link = array_splice($link, 4);
        $link = implode('/', $link);

        return $link;
    }

    /**
     * @param string|null $path
     * @return array|string|string[]
     */
    private function _clearPath(?string $path)
    {
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return str_replace('/storage', '', $path ?: '');
    }

    /**
     * @param $name
     * @param array $arguments
     * @return $this
     */
    public function __call($name, array $arguments)
    {
        return new static();
    }
}
