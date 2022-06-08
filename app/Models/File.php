<?php

namespace App\Models;

use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\File
 *
 * @property int $id
 * @property string|null $ext
 * @property string|null $original_name
 * @property string $path
 * @property int|null $note_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Note|null $note
 * @method static Builder|File newModelQuery()
 * @method static Builder|File newQuery()
 * @method static Builder|File query()
 * @method static Builder|File whereCreatedAt($value)
 * @method static Builder|File whereExt($value)
 * @method static Builder|File whereId($value)
 * @method static Builder|File whereNoteId($value)
 * @method static Builder|File whereOriginalName($value)
 * @method static Builder|File wherePath($value)
 * @method static Builder|File whereUpdatedAt($value)
 * @mixin Eloquent
 */
class File extends Model
{
    use HasFactory;

    protected $fillable = ['path', 'note_id', 'ext', 'original_name'];

    /**
     * @return BelongsTo
     */
    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * @throws Exception
     */
    public function deleteUnused()
    {
        if (!isset($this->note->id)){
            Storage::delete($this->path);
            $this->delete();
        }
    }
}
