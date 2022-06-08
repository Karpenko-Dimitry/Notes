<?php

use Illuminate\Support\Facades\Route;
use \Illuminate\Support\Facades\Schema;
use \App\Models\Language;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group([
    'middleware' => 'locale'
], function() {
    Route::group([], function() {
        Route::post('/users/login', [\App\Http\Controllers\Api\Users\LoginController::class, 'login']);

        Route::resource('notes', \App\Http\Controllers\Api\Notes\NotesController::class, [
            'only' => [
                'index', 'show',
            ],
            'parameters' => [
                'notes' => 'notes_uid_api'
            ],
        ]);

        Route::get('/public/{file}', [\App\Http\Controllers\Api\Files\FileController::class, 'download']);

        Route::resource('users', \App\Http\Controllers\Api\Users\UsersController::class, [
            'only' => 'store',
        ]);

        Route::get('/search', [\App\Http\Controllers\Api\Notes\NotesController::class, 'search']);
    });

    Route::group([
        'middleware' => 'auth:api',
    ],function() {
        Route::resource('notes', \App\Http\Controllers\Api\Notes\NotesController::class, [
            'only' => [
                'store', 'update', 'destroy',
            ],
            'parameters' => [
                'notes' => 'notes_uid_api'
            ],
        ]);

        Route::post('/notes/{notes_uid_api}/send', [\App\Http\Controllers\Api\Notes\NotesController::class, 'share']);

        Route::get('/users/profile', [\App\Http\Controllers\Api\Users\UsersController::class, 'profile']);
        Route::patch('/users/profile', [\App\Http\Controllers\Api\Users\UsersController::class, 'update']);
        Route::post('/user/{user}/avatar', [\App\Http\Controllers\Api\Users\UsersController::class, 'storeAvatar']);

        Route::resource('users.notes', \App\Http\Controllers\Api\Users\NotesController::class, [
            'only' => ['index', 'show'],
        ]);
    });

    Route::resource('categories', \App\Http\Controllers\Api\Categories\CategoriesController::class, [
        'only' => 'index'
    ]);

    Route::resource('tags', \App\Http\Controllers\Api\Tags\TagsController::class, [
        'only' => 'index'
    ]);

    Route::resource('languages', \App\Http\Controllers\Api\Languages\LanguagesController::class, [
        'only' => 'index'
    ]);

    Route::resource('/files', \App\Http\Controllers\Api\Files\FileController::class, [
        'only' => [
            'store', 'destroy'
        ],
    ]);
    Route::get('/locales/{language}/{namespace}', [\App\Http\Controllers\Api\Languages\LanguagesController::class, 'translations']);
});



