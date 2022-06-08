<?php

namespace App\Http\Controllers\Api\Notes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Note\UpdateNoteRequest;
use App\Http\Requests\Users\Notes\IndexNotesRequest;
use App\Http\Requests\Users\Share\StoreSharedNoteRequest;
use App\Http\Resources\NoteResource;
use App\Mail\NoteShared;
use App\Models\File;
use App\Models\Note;
use App\Models\Tag;
use App\Models\User;
use App\Http\Requests\Api\Note\StoreNoteRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Builder;
use Session;

class NotesController extends Controller
{
    /**
     * @param IndexNotesRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(IndexNotesRequest $request)
    {
        $filter = [
            'user_id' => $request->get('user_id'),
            'per_page' => $request->get('per_page') ?? '5',
            'grid_notes' => $request->get('grid_notes') ?? false,
            'category' => $request->get('category') ? explode(',', $request->get('category')) : [],
            'tag' => $request->get('tag') ? explode(',', $request->get('tag')) : [],
            'query' => $request->get('query') ?? '',
        ];

        $notes = Note::where(static function(Builder $builder) use($filter) {
            $builder->where('public', true);
            $builder->whereHas( 'translations', function (Builder $builder) use ($filter) {
                $query = $filter['query'];
                $builder->where('title', 'LIKE', "%$query%");
                $builder->orWhere('content', 'LIKE', "%$query%");
            });
            $tags = $filter['tag'];

            if ($user = $filter['user_id']) {
                $builder->where('user_id', $user);
            }

            if (count($tags)) {
                $builder->whereHas('tags', function (Builder $builder) use($tags) {
                    $builder->whereIn('tag_id', $tags);
                });
            }

            $categories = $filter['category'];
            if (count($categories)) {
                $builder->whereHas('categories', function (Builder $builder) use($categories) {
                    $builder->whereIn('category_id', $categories);
                });
            }
        })->orderByDesc('id')->paginate($filter['per_page']);

        return NoteResource::collection($notes);
    }


    public function store(StoreNoteRequest $request)
    {
        $this->authorize('create', Note::class);

        $translation = $request->post('translations');

        /** @var Note $note */
        $note = Auth::user()->notes()->create(array_merge($translation, $request->only([
            'public'
        ]), [
            'uid'=> md5(rand(10, 1000) * rand(1000, 10000)),
        ],
        ));

        $note->categories()->attach($request->post('category'));

        foreach ($request->post('files', []) as $id){
            if ($file = File::find($id)){
                $file->update(['note_id' => $note->id]);
            }
        }

        if ($note && $request->file('user_file')) {
            foreach ($request->file('user_file', []) as $file) {
                if (is_uploaded_file($file)) {
                    $path = $file->storePublicly('public');
                    $note->files()->create(['path' => $path]);
                }
            }
        }

        if ($note && $request->input('tags')) {
            foreach ($request->input('tags', []) as $tagName) {

                $tag = Tag::where('name', $tagName)->first();

                if (!$tag) {
                    $tag = Tag::create(['name' => $tagName]);
                }

                $note->tags()->attach($tag->id);
            }
        }

        return new NoteResource($note);
    }

    /**
     * @param Note $note
     * @return NoteResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Note $note)
    {
        $this->authorize('viewAny', $note);
        return new NoteResource($note);
    }

    /**
     * @param UpdateNoteRequest $request
     * @param Note $note
     * @return NoteResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(StoreNoteRequest $request, Note $note)
    {
        $this->authorize('update', $note);

        $translation = $request->post('translations');

        $note->categories()->sync($request->post('category'));

        foreach ($request->post('files') as $id){
            if(File::find($id)){
                File::find($id)->update(['note_id' => $note->id]);
            }
        }
        $noteTags = [];
        foreach (($request->input('tags') ?? []) as $tagName) {

            $tag = Tag::where('name', $tagName)->first();

            if (!$tag) {
                $tag = Tag::create(['name' => $tagName]);
            }

            $noteTags[] = $tag->id;
        }

        $note->tags()->sync($noteTags);

        return new NoteResource(tap($note)->update(array_merge($translation, $request->only([
            'public'
        ]))));
    }

    /**
     * @param Note $note
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);

        $note->unlink();

        return response([], 200);
    }

    /**
     * @param Note $sharedNote
     * @param StoreSharedNoteRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function share (Note $sharedNote, StoreSharedNoteRequest $request)
    {
        $this->authorize('update', $sharedNote);

        if (!$sharedNote->public) {
            $sharedNote->update(['public' => 1]);
        }

        $user = User::where('email',$request->post('email'))->first();

        if ($user) {
            $sharedNote->sharedUsers()->attach($user->id);
            Mail::mailer('rmind')
                ->to($request->post('email'))
                ->send(new NoteShared($sharedNote, Auth::user()));
        } else {
            Mail::mailer('rmind')
                ->to($request->post('email'))
                ->send(new NoteShared($sharedNote, Auth::user()));
        }

        Session::flash('message', "You have shared your note '$sharedNote->title'.");

        return response(['message' => 'Success'], 200);
    }

}

