<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Roquie\LaravelPerPageResolver\PerPageResolverTrait;

/**
 * Model
 *
 * @mixin \Eloquent
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
abstract class Model extends BaseModel
{
    use PerPageResolverTrait;

    public function getIsDeletableAttribute()
    {
        return true;
    }

    public function getIsEditableAttribute()
    {
        return true;
    }

    public function newEloquentBuilder($query)
    {
        return new EloquentBuilder($query);
    }
}
