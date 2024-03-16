<?php

namespace App\Http\Controllers\API\Device;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Device\BulkCreateUserAPIRequest;
use App\Http\Requests\Device\BulkUpdateUserAPIRequest;
use App\Http\Requests\Device\CreateUserAPIRequest;
use App\Http\Requests\Device\UpdateUserAPIRequest;
use App\Http\Resources\Device\UserCollection;
use App\Http\Resources\Device\UserResource;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Prettus\Validator\Exceptions\ValidatorException;

class UserController extends AppBaseController
{
    /**
    * @var  UserRepository
    */
    private $userRepository;

    /**
    * @param  UserRepository $userRepository
    */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
    * User's Listing API.
    * Limit Param: limit
    * Skip Param: skip
    *
    * @param  Request $request
    *
    * @return  UserCollection
    */
    public function index(Request $request): UserCollection
    {
        $users = $this->userRepository->fetch($request);

        return new UserCollection($users);
    }

    /**
    * Create User with given payload.
    *
    * @param  CreateUserAPIRequest $request
    *
    * @throws  ValidatorException
    * @return  UserResource
    */
    public function store(CreateUserAPIRequest $request): UserResource
    {
        $input = $request->all();
        if(isset($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }
        $user = $this->userRepository->create($input);
        return new UserResource($user);
    }

    /**
    * Get single User record.
    *
    * @param  int $id
    *
    * @return  UserResource
    */
    public function show(int $id): UserResource
    {
        $user = $this->userRepository->findOrFail($id);
        return new UserResource($user);
    }

    /**
    * Update User with given payload.
    *
    * @param  UpdateUserAPIRequest $request
    * @param  int $id
    *
    * @throws  ValidatorException
    * @return  UserResource
    */
    public function update(UpdateUserAPIRequest $request, int $id): UserResource
    {
        $input = $request->all();
        if(isset($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }
        $user = $this->userRepository->update($input,$id);
        return new UserResource($user);
    }

    /**
    * Delete given User.
    *
    * @param  int $id
    *
    * @throws  Exception
    * @return  JsonResponse
    */
    public function delete(int $id): JsonResponse
    {
        $this->userRepository->delete($id);
    
        return $this->successResponse('User deleted successfully.');
    }


    /**
    * Bulk create User's.
    *
    * @param  BulkCreateUserAPIRequest $request
    *
    * @throws  ValidatorException
    * @return  UserCollection
    */
    public function bulkStore(BulkCreateUserAPIRequest $request): UserCollection
    {
        $users = collect();

        $input = $request->get('data');
        foreach ($input as $key => $userInput) {
            $users[$key] = $this->userRepository->create($userInput);
        }
        return new UserCollection($users);
    }

    /**
    * Bulk update User's data.
    *
    * @param  BulkUpdateUserAPIRequest $request
    *
    * @throws  ValidatorException
    * @return  UserCollection
    */
    public function bulkUpdate(BulkUpdateUserAPIRequest $request): UserCollection
    {
        $users = collect();

        $input = $request->get('data');
        foreach ($input as $key => $userInput) {
            $users[$key] = $this->userRepository->update($userInput,$userInput['id']);
        }
        return new UserCollection($users);
    }
}
