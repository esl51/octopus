<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as BasePermission;
use Roquie\LaravelPerPageResolver\PerPageResolverTrait;

class Permission extends BasePermission
{
    use PerPageResolverTrait;

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
