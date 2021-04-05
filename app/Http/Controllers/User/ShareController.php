<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\Share\StoreSharedNoteRequest;
use App\Mail\NoteShared;
use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Session;

class ShareController extends Controller
{
    /**
     * @param Note $sharedNote
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Note $sharedNote)
    {
        $this->authorize('update', $sharedNote);

        return view('note.share', [
           'note' =>  $sharedNote,

        ]);
    }

    /**
     * @param Note $sharedNote
     * @param StoreSharedNoteRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Note $sharedNote, StoreSharedNoteRequest $request)
    {
        $this->authorize('update', $sharedNote);

        if (!$sharedNote->public){
            $sharedNote->update(['public' => 1]);
        }

        $user = User::where('email',$request->post('email'))->first();

        if ($user) {
            $sharedNote->sharedUsers()->attach($user->id);
        }

        Mail::mailer('rmind')->to(
            $request->post('email')
        )->send(new NoteShared($sharedNote, Auth::user()));

        Session::flash('message', __('common.share_message', ['note' => $sharedNote->title]));

        return back();
    }
}
