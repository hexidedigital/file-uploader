@php
    /**
     * @var string|null $src
     * @var string|null $alt
     * @var string|null $id
     * @var string|null $class
     * @var int|null $size
     */
@endphp

@php($size = $size ?? 150)

<img src="{{ !empty($src) ? FileUploader::url($src) : asset("/img/800x800.png") }}"
     {{isset($alt)?'alt='.$alt.'':''}} {{isset($id) ? 'id='.$id.'' : ''}} class="{!! $class??'' !!}"
     style="max-width: {{$size}}px; max-height: {{$size}}px">
