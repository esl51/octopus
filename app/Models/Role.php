<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as BaseRole;
use Roquie\LaravelPerPageResolver\PerPageResolverTrait;

class Role extends BaseRole
{
    use PerPageResolverTrait;
    use HasFactory;

    /**
     * @inheritdoc
     */
    public function getIsDeletableAttribute()
    {
        if ($this->name == 'root') {
            return false;
        }
        if ($this->users()->count()) {
            return false;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getIsEditableAttribute()
    {
        if ($this->name == 'root') {
            return false;
        }
        return true;
    }
}
