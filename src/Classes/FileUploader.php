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
    private $disk = 'public';
    private $storage;

    public function __construct()
    {
        $this->storage = Storage::disk($this->disk);
    }

    /**
     * @param string $disk
     * @return $this
     */
    public function disk(string $disk): self
    {
        $this->disk = $disk;
        $this->storage = Storage::disk($this->disk);
        return $this;
    }

    /**
     * @return string
     */
    public function getDiskName(): string
    {
        return $this->disk;
    }

    /**
     * @param string|null $path
     * @return string
     */
    public function url(?string $path): string
    {
        if (empty($path)) return '';
        if (Str::startsWith($path, 'http')) return $path;

        $path = $this->_clearPath($path);

        return $this->storage->url($path);
    }

    /**
     * @param string|null $path
     * @return string
     */
    public function path(?string $path): string
    {
        if (empty($path)) return '';
        if (Str::startsWith($path, 'http')) return $path;

        $path = $this->_clearPath($path);

        return $this->storage->path($path);
    }

    /**
     * @param string|null $path
     * @return bool
     */
    public function exists(?string $path): bool
    {
        if (empty($path)) return false;

        $path = $this->_clearPath($path);

        return $this->storage->exists($path);
    }

    /**
     * @param string|null $path
     * @return bool
     */
    public function delete(?string $path): bool
    {
        if (empty($path)) return false;

        $path = $this->_clearPath($path);

        return $this->storage->delete($path);
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

        return $this->storage->putFile($path, new File($file->getPathname())) ?? null;
    }

    /**
     * @param string $from
     * @param string $to
     * @return bool
     */
    public function move(string $from, string $to): bool
    {
        $from = $this->_clearPath($from);

        return $this->storage->move($from, $to);
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
     * @param string|null $path
     * @return array|string|string[]
     */
    private function _clearPath(?string $path)
    {
        return str_replace('storage/', '', $path ?: '');
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
