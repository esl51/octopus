<?php

namespace App\Models;

class PageTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
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

    public function getUrlAttribute()
    {
        $base = '';
        if ($this->locale !== config('app.fallback_locale')) {
            $base .= $this->locale . '/';
        }
        $suffix = $this->slug == 'home' ? '' : $this->slug;
        return url($base . $suffix);
    }
}
