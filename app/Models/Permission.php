<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as BasePermission;
use Roquie\LaravelPerPageResolver\PerPageResolverTrait;

class Permission extends BasePermission
{
    use PerPageResolverTrait;
    use HasFactory;

    /**
     * @inheritdoc
     */
    public function getIsDeletableAttribute()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getIsEditableAttribute()
    {
        return true;
    }
}
