<?php

namespace App\Http\Resources;

use App\Models\Note;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class NoteResource
 * @property Note $resource
 * @package App\Http\Resources
 */
class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge($this->resource->only([
            'id', 'title', 'content', 'uid', 'public', 'user_id'
        ]), [
            'categories' => CategoryResource::collection($this->resource->categories),
            'created_at' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'created_at_locale' => format_datetime_locale($this->resource->created_at),
            'user' => new UserResource($this->resource->user),
            'files' => FileResource::collection($this->resource->files),
            'translation' => $this->resource->getTranslationsArray(),
            'tags' => TagResource::collection($this->resource->tags),
            'can_edit' => auth('api')->user() && auth('api')->user()->can('update', $this->resource),
            'can_delete' => auth('api')->user() && auth('api')->user()->can('delete', $this->resource),
        ]);
    }
}
