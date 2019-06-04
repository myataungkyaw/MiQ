<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Criteria\UserCriteria;
use App\Http\Requests\API\Dashboard\CreateUserAPIRequest;
use App\Http\Requests\API\Dashboard\UpdateUserAPIRequest;
use App\Models\Dashboard\User;
use App\Repositories\Dashboard\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Hash;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Response;

/**
 * Class UserController
 * @package App\Http\Controllers\API\Dashboard
 */

class UserAPIController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     * GET|HEAD /users
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->userRepository->pushCriteria(new UserCriteria($request));
        $this->userRepository->pushCriteria(new LimitOffsetCriteria($request));
        $users = $this->userRepository->all();
        $roles = config('miq.roles');
        foreach($users as $user){
            $user->role = (isset($roles[$user->role])) ? $roles[$user->role] : 'N.A';
        }

        return $this->sendResponse($users->toArray(), 'Users retrieved successfully');
    }

    /**
     * Store a newly created User in storage.
     * POST /users
     *
     * @param CreateUserAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateUserAPIRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->password);

    

        $users = $this->userRepository->create($input);

          //  $input['company_id'] = 
      if(isset($input['company_id'])){
        \App\Models\Dashboard\CompanyUser::create([
            "company_id"=>$input['company_id'],
            "user_id"=> $users->id
        ]);
    }

        return $this->sendResponse($users->toArray(), 'User saved successfully');
    }

    /**
     * Display the specified User.
     * GET|HEAD /users/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var User $user */
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        return $this->sendResponse($user->toArray(), 'User retrieved successfully');
    }

    /**
     * Update the specified User in storage.
     * PUT/PATCH /users/{id}
     *
     * @param  int $id
     * @param UpdateUserAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserAPIRequest $request)
    {
        $input = $request->all();

        /** @var User $user */
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        if(!empty($input['new_password'])){
            $input['password'] =  Hash::make($input['new_password']);
        }

        $user = $this->userRepository->update($input, $id);

        return $this->sendResponse($user->toArray(), 'User updated successfully');
    }

    /**
     * Remove the specified User from storage.
     * DELETE /users/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var User $user */
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        $user->delete();

        return $this->sendResponse($id, 'User deleted successfully');
    }

    public function changePasswordByForce($id, Request $request){
        $input['password'] = Hash::make($request->changePassword);
        $user = $this->userRepository->update($input, $id);
        return $this->sendResponse($user->toArray(), 'Change password successfully');
    }
}
