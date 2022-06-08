<?php

namespace App\Http\Resources;

use App\Models\File;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class FileResource
 * @property File $resource
 * @package App\Http\Resources
 */
class FileResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var File $file */
        $file = $this->resource;
        return array_merge($this->resource->only(['id', 'path']), [
            'name' => $file->original_name . '.' . $file->ext,
        ]);
    }
}
