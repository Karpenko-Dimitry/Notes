<?php

namespace App\Http\Controllers\Api\Files;

use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use App\Models\File;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    /**
     * @param $file
     * @return StreamedResponse
     */
    public function download($file)
    {
        $path = 'public/' . $file;

        return Storage::download($path);
    }

    public function store(Request $request) {
        $file = $request->file('user_file')[0];

        if ($file && is_uploaded_file($file)) {
            $original_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $path = $file->storePublicly('public');
            $file = File::create(compact('path', 'original_name', 'ext'));
        }

        return new FileResource($file);
    }

    /**
     * @param File $file
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function destroy(File $file) {

        Storage::delete($file->path);

        $file->delete();

        return response()->json(['message' => 'success']);
    }
}

