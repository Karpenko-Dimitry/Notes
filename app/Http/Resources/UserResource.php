<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @property User $resource
 * @package App\Http\Resources
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(
            $this->resource->only(['id', 'name']),
            [
                'avatar' => avatarPath($this->resource->avatar),
                'created_at' => format_datetime_locale($this->resource->created_at),
            ]
        );
    }
}
