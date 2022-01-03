<?php

namespace HexideDigital\FileUploader\Traits;

use HexideDigital\FileUploader\Facades\FileUploader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait FileUploadingTrait
{
    /**
     * @param array<UploadedFile|mixed|null> $images
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
            \Storage::disk('public')->delete($old_path);
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
     * @param Request $request
     * @param Model|null $model
     * @param array $options <table>
     *  <tr>    <th>key of option</th>        <th>Default</th>        </tr>
     *  <tr>    <td>field_key</td>      <td>image</td>      </tr>
     *  <tr>    <td>folder</td>     <td>images</td>     </tr>
     *  <tr>    <td>module</td>     <td>NULL</td>       </tr>
     * </table>
     *
     * @return false|string|null
     */
    public function handleOneImage(Request $request, ?Model $model = null, array $options = [])
    {
        $path = false;

        $options['field_key'] = Arr::get($options, 'field_key', 'image');
        $options['folder'] = Arr::get($options, 'folder', 'images');
        $options['module'] = Arr::get($options, 'module', isset($model->id) ? $model->getTable() : null);

        $old_path = $model->{$options['field_key']} ?? null;

        if ($request->hasFile('image') || $request->input('isRemoveImage', false)) {
            $path = $this->saveImage(
                $request->file('image'),
                $request->input('slug'),
                $old_path,
                $options['folder'],
                $options['module'] ?? null,
            );
        }

        return $path;
    }

    private function _preparePath(string $root, ?string $uniq_id = null): string
    {
        $hash = md5($uniq_id ?? Str::random());

        $a = substr($hash, 0, 2);
        $b = substr($hash, 2, 2);

        return $root . '/' . $a . '/' . $b;
    }
}
