<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\Notes\IndexNotesRequest;
use App\Models\Category;
use App\Models\Note;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param IndexNotesRequest $request
     * @param User $user
     * @return Application|Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(IndexNotesRequest $request, User $user)
    {
        $this->authorize('view', $user);

        $filter = [
            'per_page' => $request->get('per_page') ?? '5',
            'grid_notes' => $request->get('grid_notes') ?? false,
            'shared' => $request->get('shared') ?? false,
            'category' => $request->get('category') ?? [],

        ];

        $notes = Note::where(static function(Builder $builder) use($user, $filter) {
            if (!$filter['shared']) {
                $builder->where('user_id', $user->id);
            }

            foreach ($filter['category'] as $id) {
                $builder->whereHas('categories', function (Builder $builder) use($id) {
                    $builder->where('category_id', $id);
                });
            }

            $builder->orWhereHas('sharedUsers',static function(Builder $builder) use($user){
                $builder->where('user_id', $user->id);
            });

        })->orderByDesc('created_at')->paginate($filter['per_page']);

        return view('note.index', [
            'title' => trans_choice('title.user_notes_title', 2, ['user' => $user->name]),
            'user' => $user,
            'notes' => $notes,
            'categoriesList' => Category::all(),
            'filter' => $filter,

        ]);
    }
}
