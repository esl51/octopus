<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\ItemController;
use App\Http\Resources\Access\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends ItemController
{
    protected $class = User::class;
    protected $resourceClass = UserResource::class;
    protected $with = [
        'roles',
    ];

    /**
     * @inheritdoc
     */
    public function getValidationRules(Request $request, $id = null)
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email' . ($id ? ",$id" : ''),
            'password' => ($id ? 'nullable' : 'required') . '|min:6|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'nullable|integer',
        ];
    }

    /**
     * @inheritdoc
     */
    protected function newItemsQuery(Request $request)
    {
        $items = parent::newItemsQuery($request);

        $search = filter_var($request->search, FILTER_SANITIZE_STRING);
        if ($search) {
            $items->orWhere('users.name', 'like', '%' . $search . '%')
                ->orWhere('users.email', 'like', '%' . $search . '%');
        }

        $items->leftJoin('model_has_roles', function ($join) {
            $join->on('model_has_roles.model_id', '=', 'users.id')
                ->where('model_has_roles.model_type', '=', User::class);
        });

        $role = filter_var($request->role, FILTER_SANITIZE_STRING);
        if ($role) {
            $items->join('roles', 'roles.id', '=', 'model_has_roles.role_id');
            $items->orWhere('roles.name', $role);
        }

        $items->orderByRaw('count(model_has_roles.role_id) desc, name asc');

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
        $data = $request->only('name', 'email');
        $data['password'] = Hash::make($request->password);
        $data['email_verified_at'] = now();
        $user = User::create($data);

        $roles = $request->roles;
        $rootRole = Role::where('name', 'root')->first();
        // if current user is not root - unset root role from new roles
        if (!Auth::user()->hasRole('root')) {
            if (($key = array_search($rootRole->id, $roles)) !== false) {
                unset($roles[$key]);
            }
        }
        $user->syncRoles($roles);

        return $this->show($user->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $this->getItem($id);
        if (!$user->is_editable) {
            return response()->json(["message" => trans('app.not_editable')], 400);
        }
        $this->validate($request, $this->getValidationRules($request, $id));
        $data = $request->only('name', 'email');
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);

        $roles = $request->roles;
        $rootRole = Role::where('name', 'root')->first();
        // if user is current user and current user is root and root was unchecked - set only root role
        if ($user->id == Auth::user()->id && Auth::user()->hasRole('root') && !in_array($rootRole->id, $roles)) {
            $roles = [$rootRole->id];
        }
        // if current user is not root and user is not root - unset root role from new roles
        if (!Auth::user()->hasRole('root') && !$user->hasRole('root')) {
            if (($key = array_search($rootRole->id, $roles)) !== false) {
                unset($roles[$key]);
            }
        }
        $user->syncRoles($roles);

        return $this->show($user->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->getItem($id);
        if (!$user->is_deletable) {
            return response()->json(["message" => trans('app.not_deletable')], 400);
        }
        $user->delete();

        return response()->json(null, 204);
    }

    /**
     * Get authenticated user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function current(Request $request)
    {
        $user = $request->user();
        $user->append('can', 'all_permissions');
        $user->makeVisible('roles');
        return response()->json($user);
    }
}
