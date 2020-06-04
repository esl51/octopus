<?php

namespace App\Http\Resources\Access;

use App\Http\Resources\ItemResource;

class UserResource extends ItemResource
{
    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        $this->makeVisible('roles');
        return parent::toArray($request);
    }
}
