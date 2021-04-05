<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Note;
use App\Models\User;

class NoteShared extends Mailable
{
    use Queueable, SerializesModels;

    protected $note;
    protected $user;

    /**
     * Create a new message instance.
     *
     * @param Note $note
     * @param User $user
     */
    public function __construct(Note $note, User $user)
    {
        $this->note = $note;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->markdown('mails.notes.shared')
                    ->with([
                        'note' => $this->note,
                        'user' => $this->user,
                    ]);
    }
}
