<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Status
 *
 * @property string $variant
 * @property boolean $is_published
 * @property boolean $is_default
 * @property string $name
 *
 * @property User $author
 * @property Status $status
 */
class Status extends Model
{
    use Translatable;
    use HasFactory;

    protected $fillable = [
        'variant',
        'is_published',
        'is_default',
        'name',
    ];

    protected $translatedAttributes = [
        'name',
    ];

    /**
     * @inheritdoc
     */
    public function getIsDeletableAttribute()
    {
        return !$this->is_published && !$this->is_default;
    }

    /**
     * Get default status.
     *
     * @return self
     */
    public static function getDefault()
    {
        return self::where('is_default', true)->first();
    }

    /**
     * Get published status.
     *
     * @return self
     */
    public static function getPublished()
    {
        return self::where('is_published', true)->first();
    }

    /**
     * @inheritdoc
     */
    public static function boot()
    {
        parent::boot();

        self::saved(function ($model) {

            // remove is_published flag from other statuses
            if ($model->is_published) {
                self::where('is_published', true)
                    ->where('id', '!=', $model->id)
                    ->update(['is_published' => false]);
            }

            // remove is_default flag from other statuses
            if ($model->is_default) {
                self::where('is_default', true)
                    ->where('id', '!=', $model->id)
                    ->update(['is_default' => false]);
            }
        });
    }
}
