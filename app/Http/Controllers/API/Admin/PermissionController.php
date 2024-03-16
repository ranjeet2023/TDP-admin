<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\AppBaseController;
use App\Http\Resources\Admin\PermissionCollection;
use App\Http\Resources\Admin\PermissionResource;
use App\Repositories\PermissionRepository;
use Illuminate\Http\Request;

class PermissionController extends AppBaseController
{
    /**
     * @var  PermissionRepository 
     */
    private $permissionRepository;

    /**
     * @param  PermissionRepository $permissionRepository
     */
    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
    * Return lists of permissions.
    *
    * @param  Request $request
    *
    * @return  PermissionCollection
    */
    public function index(Request $request): PermissionCollection
    {
        $permissions = $this->permissionRepository->all(
            $request->all(),
            $request->get('skip', null),
            $request->get('limit', null),
        );

        return new PermissionCollection($permissions);
    }

    /**
    * Return permission with given ID.
    *
    * @param  int $id
    *
    * @return  PermissionResource
    */
    public function show(int $id): PermissionResource
    {
        $permission = $this->permissionRepository->findOrFail($id);

        return new PermissionResource($permission);
    }
}
