<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Spatie\MediaLibrary\Media;

class FormMediaTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Media $media
     * @return array
     */
    public function transform(Media $media)
    {
        return [
            'id' => $media->id,
            'file_name' => $media->file_name,
            'size' => $media->size,
            'created_at' => $media->created_at->diffForHumans(),
        ];
    }
}
