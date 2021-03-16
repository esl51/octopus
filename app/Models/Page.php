<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;

/**
 * Page
 *
 * @property integer $author_id
 * @property integer $status_id
 * @property string $slug
 * @property string $title
 * @property string $headline
 * @property string $body
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 *
 * @property User $author
 * @property Status $status
 */
class Page extends Model
{
    use Translatable;

    protected $fillable = [
        'author_id',
        'status_id',
    ];

    protected $translatedAttributes = [
        'slug',
        'title',
        'headline',
        'body',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $appends = [
        'url',
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function getUrlAttribute()
    {
        $locale = app()->getLocale();
        $base = '';
        if ($locale !== config('app.fallback_locale')) {
            $base .= $locale . '/';
        }
        $suffix = $this->slug == 'home' ? '' : $this->slug;
        return url($base . $suffix);
    }

    /**
     * Scope a query to only include published pages.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('status_id', Status::getPublished()->id);
    }

    /**
     * @inheritdoc
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {

            $model->author_id = auth()->id();
        });
    }
}
