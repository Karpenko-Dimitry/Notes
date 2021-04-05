<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class NotesController extends Controller
{
    /**
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(User $user, Request $request) {

        $this->authorize('view', $user);

        $filter = [
            'per_page' => $request->get('per_page') ?? '5',
            'grid_notes' => $request->get('grid_notes') ?? false,
            'shared' => $request->get('shared') ?? false,
            'category' => explode(',', $request->get('category')) ?? [],

        ];

        $notes = Note::where(static function(Builder $builder) use($user, $filter) {
            if (!$filter['shared']) {
                $builder->where('user_id', $user->id);
            }


            $builder->orWhereHas('sharedUsers',static function(Builder $builder) use($user){
                $builder->where('user_id', $user->id);
            });

            $categories = $filter['category'];
            $builder->whereHas('categories', function (Builder $builder) use($categories) {
                foreach ($categories as $key => $id) {
                    if ($id != null) {
                        $key === 0 ? $builder->where('category_id', $id) : $builder->orWhere('category_id', $id);
                    }
                }
            });

        })->orderByDesc('created_at')->paginate($filter['per_page']);

        return NoteResource::collection($notes);

    }
}

