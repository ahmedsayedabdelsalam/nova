<?php

namespace Code95\MediaLibrary\Services;

use Illuminate\Database\Eloquent\Model;

class MediaLibraryService
{
    static public function attachMediaToModel(Model $model, array $media_ids)
    {
        $model->media()->sync($media_ids);
        return $model;
    }
}
