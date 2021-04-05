<?php

namespace App\Http\Controllers\Api\Tags;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;

class TagsController extends Controller
{
    public function index()
    {
        return TagResource::collection(Tag::all());
    }
}
