<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Admin\CreateUserRoleAPIRequest;
use App\Http\Requests\Admin\BulkCreateUserRoleAPIRequest;
use App\Http\Requests\Admin\UpdateUserRoleAPIRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserRoleController extends AppBaseController
{
    /**
    * Assign role to specific user
    *
    * @param  CreateUserRoleAPIRequest  $request
    *
    * @return  JsonResponse
    */
    public function assignRole(CreateUserRoleAPIRequest $request): JsonResponse
    {
        $input = $request->all();
        $user = User::findOrFail($input['user_id']);
        $role = Role::findOrFail($input['role_id']);
        if(!empty($role)) {
            $user->roles()->sync($input['role_id']);
        }
        
        return $this->successResponse('Role assign successfully');
    }

    /**
    * Return roles assign to user
    *
    * @param  $id
    *
    * @return  JsonResponse
    */
    public function getAssignedRoles($id): JsonResponse
    {
        $user = User::findOrFail($id);
        $roles = $user->getRoleNames();

        return $this->successResponse($roles);
    }

    /**
    * Update assigned role of user
    *
    * @param  $id
    * @param  UpdateUserRoleAPIRequest  $request
    *
    * @return  JsonResponse
    */
    public function updateUserRole($id,UpdateUserRoleAPIRequest $request): JsonResponse
    {
        $input = $request->all();
        $user = User::findOrFail($id);
        $role = Role::findOrFail($input['role_id']);

        if(!empty($role)) {
            $user->roles()->sync($input['role_id']);
        }

        return $this->successResponse('Role updated successfully');
    }

    /**
    * Assign multiple role to specific users
    *
    * @param  BulkCreateUserRoleAPIRequest  $request
    *
    * @return  JsonResponse
    */
    public function bulkAssignRole(BulkCreateUserRoleAPIRequest $request): JsonResponse
    {
        $input = $request->get('data');
        foreach ($input as $rolesInput) {
            $user = User::findOrFail($rolesInput['user_id']);
            $role = Role::findOrFail($rolesInput['role_id']);
            
            if(!empty($role)){      
                $user->roles()->sync($rolesInput['role_id']);
            }
        }

        return $this->successResponse('Role assign successfully');
    }
}
