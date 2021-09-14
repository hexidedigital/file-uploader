<?php

namespace HexideDigital\FileUploader\Classes;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploader
{

    /**
     * @var string
     */
    private string $disk = 'public';

    public function disk(string $disk): self
    {
        $this->disk = $disk;
        return $this;
    }

    public function url(?string $path): string
    {
        if(empty($path)) return '';

        $path = $this->_clearPath($path);

        return Storage::disk($this->disk)->url($path);
    }

    public function exists(?string $path): bool
    {
        $path = $this->_clearPath($path);
        return Storage::disk($this->disk)->exists($path);
    }

    public function delete(?string $path): bool
    {
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
        if(empty($file)){
            return null;
        }

        $module = empty($module)? '': '/'.$module;

        $path = $this->_preparePath($type . $module, $uniq_id);

        return Storage::disk($this->disk)->putFile($path, new File($file->getPathname())) ?? null;
    }

    public function move(string $from, string $to): bool
    {
        $from = $this->_clearPath($from);

        $to = $this->_prepareToMove($to);

        return Storage::disk($this->disk)->move($from, $to);
    }

    private function _preparePath(string $root, ?string $uniq_id = null): string
    {
        $hash = md5($uniq_id ?? Str::random());

        $a = substr($hash, 0, 2);
        $b = substr($hash, 2, 2);

        return $root . '/' . $a . '/' . $b;
    }

    private function _prepareToMove(string $link): string
    {
        $link = explode('/', $link);
        $link = array_splice($link, 4);
        $link = implode('/', $link);

        return $link;
    }

    private function _clearPath(string $path)
    {
        return str_replace('/storage','', $path);
    }

    public function __call($name, array $arguments)
    {
        return new static();
    }
}
