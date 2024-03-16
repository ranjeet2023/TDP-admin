<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Admin\BulkCreateRoleAPIRequest;
use App\Http\Requests\Admin\BulkUpdateRoleAPIRequest;
use App\Http\Requests\Admin\CreateRoleAPIRequest;
use App\Http\Requests\Admin\UpdateRoleAPIRequest;
use App\Http\Resources\Admin\RoleCollection;
use App\Http\Resources\Admin\RoleResource;
use App\Repositories\RoleRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Prettus\Validator\Exceptions\ValidatorException;

class RoleController extends AppBaseController
{
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
    * Return Lists of roles.
    *
    * @param  Request $request
    *
    * @return  RoleCollection
    */
    public function index(Request $request): RoleCollection
    {
        $roles = $this->roleRepository->all(
            $request->all(),
            $request->get('skip', null),
            $request->get('limit', null),
        );

        return new RoleCollection($roles);
    }

    /**
    * Create new roles with given permissions.
    *
    * @param  CreateRoleAPIRequest $request
    *
    * @throws  ValidatorException
    * @return  RoleResource
    */
    public function store(CreateRoleAPIRequest $request): RoleResource
    {
        $input = $request->all();
        $input['guard_name'] = 'web';
        $role = $this->roleRepository->create($input);

        if (isset($input['permissions']) && ! empty($input['permissions'])) {
            $role->syncPermissions($input['permissions']);
        }

        return new RoleResource($role);
    }

    /**
    * Return role with given ID.
    *
    * @param  int $id
    *
    * @return  RoleResource
    */
    public function show(int $id): RoleResource
    {
        $role = $this->roleRepository->findOrFail($id);

        return new RoleResource($role);
    }

    /**
    * Update role with given payload.
    *
    * @param  UpdateRoleAPIRequest $request
    * @param  int $id
    *
    * @throws  ValidatorException
    * @return  RoleResource
    */
    public function update(UpdateRoleAPIRequest $request,int $id): RoleResource
    {
        $input = $request->all();
        $input['guard_name'] = 'web';
        $role = $this->roleRepository->update($input,$id);

        if (isset($input['permissions']) && ! empty($input['permissions'])) {
            $role->syncPermissions($input['permissions']);
        }

        return new RoleResource($role);
    }

    /**
    * Delete role with given ID.
    *
    * @param  int $id
    *
    * @throws  Exception
    * @return  JsonResponse
    */
    public function delete(int $id): JsonResponse
    {
        $this->roleRepository->delete($id);

        return $this->successResponse('Role deleted successfully.');
    }

    /**
    * Create multiple roles with related permissions.
    *
    * @param  BulkCreateRoleAPIRequest $request
    *
    * @throws  ValidatorException
    * @return  RoleCollection
    */
    public function bulkStore(BulkCreateRoleAPIRequest $request): RoleCollection
    {
        $roles = collect();

        $input = $request->get('data');
        foreach ($input as $key => $roleInput) {
            $roleInput['guard_name'] = 'web';
            $roles[$key] = $this->roleRepository->create($roleInput);
            if (isset($input['permissions']) && ! empty($input['permissions'])) {
                $roles[$key]->syncPermissions($roleInput['permissions']);
            }
        }

        return new RoleCollection($roles);
    }

    /**
    * Update multiple roles with given payload.
    *
    * @param  BulkUpdateRoleAPIRequest $request
    *
    * @throws  ValidatorException
    * @return  RoleCollection
    */
    public function bulkUpdate(BulkUpdateRoleAPIRequest $request): RoleCollection
    {
        $roles = collect();

        $input = $request->get('data');
        foreach ($input as $key => $roleInput) {
            $roleInput['guard_name'] = 'web';
            $roles[$key] = $this->roleRepository->update($roleInput,$roleInput['id']);
            if (isset($input['permissions']) && ! empty($input['permissions'])) {
                $roles[$key]->syncPermissions($roleInput['permissions']);
            }
        }

        return new RoleCollection($roles);
    }
}
