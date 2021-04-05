<?php

namespace App\Http\Controllers\Api\Languages;

use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageResource;
use App\Models\Language;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class LanguagesController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return LanguageResource::collection(Language::all());
    }

    /**
     * @param $language
     * @param $namespace
     * @return array|array[]|Application|ResponseFactory|Response|null[]|\null[][]|string[]|string[][]|string[][][]
     */
    public function translations($language, $namespace)
    {
        $replaceKey = function ($key) {

            if (is_array($key)) {
                $replaceKey = function ($key) {
                    return preg_replace('/:(\w+)/', '{{$1}} ', $key);
                };
                return array_map($replaceKey, $key);
            }

            return preg_replace('/:(\w+)/', '{{$1}} ', $key);
        };

        if (is_array(trans($namespace, [], $language))) {
            return array_map($replaceKey, trans($namespace, [], $language));
        }

        return response('Bad request', 400);

    }
}
