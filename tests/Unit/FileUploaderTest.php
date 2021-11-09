<?php

namespace HexideDigital\FileUploader\Tests\Unit;

use HexideDigital\FileUploader\Classes\FileUploader;
use HexideDigital\FileUploader\Tests\BaseTestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploaderTest extends BaseTestCase
{
    private $disk = 'some_disk';
    private $fu;
    private $storage;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake($this->disk);
        $this->storage = Storage::disk($this->disk);

        $this->fu = (new FileUploader())->disk($this->disk);
    }

    public function test_setting_disk()
    {
        $fu = new FileUploader();

        $this->assertEquals($fu, $fu->disk('some_disk'));
        $this->assertEquals('some_disk', $fu->getDiskName());
    }

    public function test_put_method()
    {
        $file = UploadedFile::fake()->image('some_avatar.png');

        $file_path = $this->fu->put($file, 'avatar', 'user', 1);

        $root = 'avatar/user';

        /*prepare path*/
        $hash = md5(1);
        $a = substr($hash, 0, 2);
        $b = substr($hash, 2, 2);
        $path = "$root" . "/" . $a . "/" . $b;

        $this->assertNotEmpty($file_path);
        $this->assertStringStartsNotWith('/', $file_path);
        $this->assertTrue($this->storage->has($path));
        $this->assertTrue($this->storage->has($file_path));
    }

    public function test_path_method()
    {
        $storage_path = $this->storage->path('path/to/image.png');
        $fu_path = $this->fu->path('path/to/image.png');
        $this->assertEquals($storage_path, $fu_path);

        $storage_path = $this->storage->path('path/to/image.png');
        $fu_path = $this->fu->path('storage/path/to/image.png');
        $this->assertEquals($storage_path, $fu_path);

        $url_path = url('to/some/image.png');
        $fu_path = $this->fu->path($url_path);
        $this->assertEquals($url_path, $fu_path);
        $this->assertStringStartsWith('http', $fu_path);
    }

    public function test_url_method()
    {
        $path = 'path/to/image.png';
        $this->storage->put($path, '');

        $image_url = $this->fu->url($path);

        $this->assertNotEmpty($image_url);
        $this->assertStringContainsString('/storage/', $image_url);
        $this->assertEquals('/storage/'.$path, $image_url);
    }
}
