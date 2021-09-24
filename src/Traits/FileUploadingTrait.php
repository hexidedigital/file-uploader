<?php

namespace HexideDigital\FileUploader\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use HexideDigital\FileUploader\Facades\FileUploader;

trait FileUploadingTrait
{

    /**
     * @param array $images
     * @param string|null $uniq_id to place in the same folder
     * @param string|null $type default is `photos`
     * @param string|null $module
     * @return array[]
     */
    public function saveImages(array $images, string $type = null, string $uniq_id = null, string $module = null): array
    {
        $result = [];

        foreach ($images as $image) {
            $result[] = $this->saveImage($image, $uniq_id, null, $type, $module);
        }

        return ['attached' => $result];
    }

    /**
     * @param UploadedFile|mixed|null $image
     * @param string|null $uniq_id to place in the same folder
     * @param string|null $old_path to delete old photo
     * @param string|null $type default is `images`
     * @param string|null $module
     * @return string|null
     */
    public function saveImage($image,
                              string $uniq_id = null, string $old_path = null,
                              string $type = null, string $module = null): ?string
    {
        if (!empty($old_path)) {
            $this->deleteImage($old_path);
        }

        if (empty($module)) {
            $module = $this->module ?? null;
        }

        if (empty($type)) {
            $type = 'images';
        }

        return FileUploader::put($image, $type, $module, $uniq_id) ?? null;
    }

    /**
     * @param string|null $path
     * @return bool
     */
    public function deleteImage(?string $path): bool
    {
        return FileUploader::delete($path);
    }

    /**
     * @param Request $request
     * @param Model|null $model
     * @param array $options =
     * [
     *  'field_key' => 'image',
     *  'folder' => 'images',
     *  'module' => null,
     * ]
     * @return bool
     */
    public function handleOneImage(Request $request, ?Model $model = null, array $options = []): bool
    {
        $path = false;

        if(empty($options['field_key'])) $options['field_key'] = 'image';
        if(empty($options['folder'])) $options['folder'] = 'images';
        if(empty($options['module']) && $model) $options['module'] = $model->getTable() ?? null;

        $old_path = $model->{$options['field_key']} ?? null;

        if ($request->hasFile('image') || $request->input('isRemoveImage', false)) {
            $path = $this->saveImage(
                $request->file('image'),
                $request->input('slug'),
                $old_path,
                $options['folder'],
                $options['module']??null,
            );
        }

        return $path;
    }
}
