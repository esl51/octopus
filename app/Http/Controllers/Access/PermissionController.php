<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\ItemController;
use App\Http\Resources\Access\PermissionResource;
use App\Permission;
use Illuminate\Http\Request;

class PermissionController extends ItemController
{
    protected $class = Permission::class;
    protected $resourceClass = PermissionResource::class;
    protected $fillable = [
        'name',
        'guard_name',
    ];

    /**
     * @inheritdoc
     */
    public function getValidationRules(Request $request, $id = null)
    {
        return [
            'name' => 'required|string|max:255',
            'guard_name' => 'required|string|max:255',
        ];
    }

    /**
     * @inheritdoc
     */
    public function newItemsQuery(Request $request)
    {
        $items = parent::newItemsQuery($request);

        $search = filter_var($request->search, FILTER_SANITIZE_STRING);
        if ($search) {
            $items->orWhere('name', 'like', '%' . $search . '%')
                ->orWhere('guard_name', 'like', '%' . $search . '%');
        }

        return $items;
    }
}
