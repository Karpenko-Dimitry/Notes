<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\NoteController;
use \App\Http\Controllers\LoginController;
use \App\Models\Language;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

if (Schema::hasTable('languages')) {
    $languages = Language::pluck('type');
}

foreach ($languages ?? [] as $lang) {
    Route::group([
        'prefix' => ($lang !== 'en' ? $lang : ''),
        'middleware' => 'locale:'.$lang
    ], function () use($lang) {
        Route::get('/', function () use ($lang) {
            return redirect(url_locale('/notes'));
        });

        Route::get('/sign-up', [UserController::class, 'create'])->name($lang === 'en' ? 'sign-up' : '')->middleware('guest');
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/user/{user}/cabinet', [UserController::class, 'profile']);
        Route::post('/user/{user}/avatar', [UserController::class, 'storeAvatar']);

        Route::middleware('guest')->group(function () use($lang)
        {
            Route::get('/sign-in', [LoginController::class, 'create'])->name($lang === 'en' ? 'sign-in' : '');
            Route::post('/sign-in', [LoginController::class, 'store']);
        });

        Route::delete('/log-out', [LoginController::class, 'destroy']);

        Route::get('/login/oauth', [LoginController::class, 'redirectToProvider']);
        Route::get('/login/google/callback', [LoginController::class, 'handleProviderCallback']);


        Route::get('/notes/{notes_uid_web}/delete', [NoteController::class, 'delete']);
        Route::get('/search', [NoteController::class, 'search']);
        Route::resource('notes', NoteController::class, [
            'except' => ['index', 'show'],
            'parameters' => [
                'notes' => 'notes_uid_web'
            ],
        ])->middleware('auth');

        Route::resource('notes', NoteController::class, [
            'only' => ['index', 'show'],
            'parameters' => [
                'notes' => 'notes_uid_web'
            ],
        ]);

        Route::get('/notes/{notes_uid_web}/share', [\App\Http\Controllers\User\ShareController::class, 'create']);
        Route::post('/notes/{sharedNote}/send', [\App\Http\Controllers\User\ShareController::class, 'store']);
        Route::get('/download/{notes_uid}', [NoteController::class, 'download']);
        Route::resource('user.notes', \App\Http\Controllers\User\NotesController::class, [
            'only' => ['index']
        ]);
        Route::resource('tag.notes', \App\Http\Controllers\Tag\NoteController::class, [
            'only' => ['index'],
        ]);
    });
}
