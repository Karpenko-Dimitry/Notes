<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Astrotomic\Translatable\Translatable;
use Parsedown;

/**
 * App\Models\Note
 *
 * @property int $id
 * @property string $uid
 * @property int $public
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\File[] $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $sharedUsers
 * @property-read int|null $shared_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\NoteTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\NoteTranslation[] $translations
 * @property-read int|null $translations_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\NoteFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Note listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Note newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Note orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Note orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Note orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Note query()
 * @method static \Illuminate\Database\Eloquent\Builder|Note translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Note translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note wherePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note withTranslation()
 * @mixin \Eloquent
 */
class Note extends Model
{
    use HasFactory, Translatable;

    protected $translatedAttributes  = [
        'title', 'content'
    ];

    protected $fillable = [
        'uid', 'file', 'public', 'user_id', 'category_id'
    ];

    /**
     * @return string
     */
    public function getParsedContent()
    {
        $parser = new Parsedown();

        return $parser->text(e($this->content));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sharedUsers()
    {
        return $this->belongsToMany(User::class, 'shared_users')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    /**
     * @throws \Exception
     */
    public function unlink()
    {
        foreach ($this->files as $file) {
            Storage::delete($file->path);
        }

        $this->delete();
    }
}
