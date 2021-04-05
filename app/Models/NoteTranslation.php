<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\NoteTranslation
 *
 * @property int $id
 * @property string $locale
 * @property string $title
 * @property string $content
 * @property int $note_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|NoteTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NoteTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NoteTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|NoteTranslation whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NoteTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NoteTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NoteTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NoteTranslation whereNoteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NoteTranslation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NoteTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class NoteTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['uid', 'title', 'content', 'file', 'public', 'user_id', 'category_id'];

    public $timestamps = false;

}
