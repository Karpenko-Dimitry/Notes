<?php

namespace App\Http\Controllers;

use App\Http\Requests\Note\UpdateNoteRequest;
use App\Models\Category;
use App\Models\Language;
use App\Models\Note;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Note\StoreNoteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Note\SearchRequest;
use function redirect;

class NoteController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function index(Request $request)
    {
        $filter = [
            'per_page' => $request->get('per_page') ?? '5',
            'grid_notes' => $request->get('grid_notes') ?? false,
            'category' => $request->get('category') ?? [],
            'tag' => $request->get('tag') ?? false,
            'query' => $request->get('query') ?? '',
        ];

        $notes = Note::where(static function(Builder $builder) use($filter) {

            $builder->where('public', true);

            $builder->whereHas( 'translations', function (Builder $builder) use ($filter) {
                $query = $filter['query'];
                $builder->where('title', 'LIKE', "%$query%");
                $builder->orWhere('content', 'LIKE', "%$query%");
            });


            $builder->whereHas('tags', function (Builder $builder) use($filter) {
                $tag = Tag::where('name', $filter['tag'])->first();
                if ($filter['tag']) {
                    $builder->where('tag_id', $tag->id);
                }
            });

            $builder->whereHas('categories', function (Builder $builder) use($filter) {
                $categories = $filter['category'];
                foreach ($categories as $key => $id) {
                    $key === 0 ? $builder->where('category_id', $id) : $builder->orWhere('category_id', $id);
                }
            });



        });

        return view('note.index', [
            'title' => __('title.notes_list_title'),
            'notes' => $notes->orderByDesc('id')->paginate($filter['per_page']),
            'categoriesList' => Category::all(),
            'filter' => $filter,

        ]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function create()
    {
        $this->authorize('create', Note::class);

        return view('note.create', [
            'categoriesList' => Category::all(),
            'tags' => Tag::getNamesArr(),
        ]);
    }

    /**
     * @param StoreNoteRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreNoteRequest $request)
    {
        $this->authorize('create', Note::class);

        $translation = [];

        foreach (Language::all() as $language) {
            if ($request->input($language->type . '_title') &&
                $request->input($language->type . '_content')) {
                $dataByLocale  = [
                    $language->type => [
                        'title' => $request->input($language->type . '_title'),
                        'content' => $request->input($language->type . '_content'),
                    ],
                ];

                $translation = array_merge($translation, $dataByLocale);
            }
        }

        /** @var Note $note */
        $note = Auth::user()->notes()->create(array_merge($translation, $request->only([
            'public'
        ]), [
            'uid'=> md5(rand(10, 1000) * rand(1000, 10000)),
        ],
        ));


        $note->categories()->attach($request->post('category'));

        if ($note && $request->file('user_file')) {
           foreach ($request->file('user_file') as $file) {
               if (is_uploaded_file($file)) {
                   $path = $file->storePublicly('public');
                   $note->files()->create(['path' => $path]);
               }
           }
        }

        if ($note && $request->input('tags')) {
            foreach ($request->input('tags') as $tagName) {

                $tag = Tag::where('name', $tagName)->first();

                if (!$tag) {
                    $tag = Tag::create(['name' => $tagName]);
                }

                $note->tags()->attach($tag->id);
            }
        }

        return redirect('/user/' . Auth::id() . '/notes');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return Note|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function show(Note $note)
    {
        return view('note.show', [
            'note' => $note,

        ]);
    }

    /**
     * @param Note $note
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Note $note)
    {
        $this->authorize('update', $note);

        $noteCategoriesArr = [];
        $noteTagsArr = [];


        foreach ($note->categories as $category) {
            $noteCategoriesArr[] = $category->id;
        }

        foreach ($note->tags as $tag) {
            $noteTagsArr[] = $tag->name;
        }

        return view('note.edit', [
            'note' => $note,
            'tags' => Tag::getNamesArr(),
            'noteCategories' => $noteCategoriesArr,
            'noteTags' => $noteTagsArr,
            'categoriesList' => Category::all(),

        ]);
    }

    /**
     * @param UpdateNoteRequest $request
     * @param Note $note
     * @return \Illuminate\Contracts\Foundation\Application|RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateNoteRequest $request, Note $note)
    {
        $this->authorize('update', $note);

        $translation = [];
        $noteTags = [];

        foreach (Language::all() as $language) {
            if ($request->input($language->type . '_title') &&
                $request->input($language->type . '_content')) {
                $lacaleData  = [
                    $language->type => [
                        'title' => $request->input($language->type . '_title'),
                        'content' => $request->input($language->type . '_content'),
                    ],
                ];

                $translation = array_merge($translation, $lacaleData);
            }
        }

        $public = $request->post('public') ?? 0;

        $note->categories()->sync($request->post('category'));

        foreach (($request->input('tags') ?? []) as $tagName) {

            $tag = Tag::where('name', $tagName)->first();

            if (!$tag) {
                $tag = Tag::create(['name' => $tagName]);
            }

            $noteTags[] = $tag->id;
        }

        $note->tags()->sync($noteTags);

        if ($note && $request->file('user_file')) {
            foreach ($request->file('user_file') as $file) {
                if (is_uploaded_file($file)) {
                    $path = $file->storePublicly('public');
                    $note->files()->create(['path' => $path]);
                }
            }
        }

        $note->update(array_merge($translation, $request->only([
            'public',
        ]),[
            'public'  => $public,
            'user_id' => $note->user_id,
            'uid'     => $note->uid,
        ]));

        return redirect('/user/' . $note->user_id . '/notes');
    }

    /**
     * @param Note $note
     * @return \Illuminate\Contracts\Foundation\Application|RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);
        $note->unlink();
        return redirect('/user/' . $note->user->id . '/notes');
    }

    /**
     * @param Note $note
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete (Note $note)
    {
        $this->authorize('update', $note);

        return view('note.delete',[
            'note' => $note,

        ]);
    }

    /**
     * @param SearchRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function search(SearchRequest $request)
    {
        $query = $request->get('query');

        $filter = [
            'per_page' => $request->get('per_page') ?? '5',
            'grid_notes' => $request->get('grid_notes') ?? false,
            'category[]' => $request->get('category') ?? [],
            'query' => $request->get('query') ?? null,
        ];

        $notes = Note::where(static function(Builder $builder) use ($query) {
            $builder->where('public', '=', 1);
            $builder->whereHas( 'translations', function (Builder $builder) use ($query) {
                $builder->where('title', 'LIKE', "%$query%");
                $builder->orWhere('content', 'LIKE', "%$query%");
            });
        })->paginate($filter['per_page']);

        return view('note.index', [
            'title' => "Search result",
            'notes' => $notes,
            'filter' => $filter,

        ]);
    }
}

