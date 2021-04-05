<?php

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Note;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * @param Request $request
     * @param Tag $tag
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request, Tag $tag) {

        $filter = [
            'per_page' => $request->get('per_page') ?? '5',
            'grid_notes' => $request->get('grid_notes') ?? false,
            'category' => $request->get('category') ?? [],
            'query' => $request->get('query') ?? null,
        ];

        $notes = Note::where(static function(Builder $builder) use($tag, $filter) {
            $builder->where('public', true);
            $builder->whereHas('tags', function (Builder $builder) use($tag) {
                $builder->where('tag_id', $tag->id);
            });
            foreach ($filter['category'] as $id) {
                $builder->whereHas('categories', function (Builder $builder) use($id) {
                    $builder->where('category_id', $id);
                });
            }
        });

        return view('note.index', [
            'title' => __('title.tag_notes_title', ['tag' => $tag->name]),
            'notes' => $notes->orderByDesc('id')->paginate($filter['per_page']),
            'tag' => $tag,
            'categoriesList' => Category::all(),
            'filter' => $filter,

        ]);
    }
}
