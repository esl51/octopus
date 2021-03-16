<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\ItemController;
use App\Http\Resources\Access\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends ItemController
{
    protected $class = Role::class;
    protected $resourceClass = RoleResource::class;
    protected $fillable = [
        'name',
        'guard_name',
    ];
    protected $with = [
        'permissions',
    ];

    /**
     * @inheritdoc
     */
    public function getValidationRules(Request $request, $id = null)
    {
        return [
            'name' => 'required|string|max:255',
            'guard_name' => 'required|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'nullable|integer',
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->getValidationRules($request));
        $role = Role::create($request->only($this->fillable));
        $role->syncPermissions($request->permissions);
        return $this->show($role->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, $this->getValidationRules($request, $id));
        $role = $this->getItem($id);
        $role->update($request->only($this->fillable));
        $role->syncPermissions($request->permissions);
        return $this->show($role->id);
    }
}
