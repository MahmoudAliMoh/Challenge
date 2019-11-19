<?php

namespace App\Http\Controllers\APIs;

use App\Http\Contracts\UsersContracts\UsersServiceContract;
use App\Http\Controllers\APIs\Traits\APIsCommonMethods;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mockery\Exception;

class UsersController extends Controller
{
    use APIsCommonMethods;

    private $userService;

    /**
     * UsersController constructor.
     * @param UsersServiceContract $userService
     */
    public function __construct(UsersServiceContract $userService)
    {
        $this->userService = $userService;
    }

    /**
     * List users with filter actions.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsersList(Request $request)
    {
        try {
            $filterInputs = $request->all();
            $users = $this->userService->mergeProviders($filterInputs);
            return $this->apiResponse('Users list.', $users, 202);
        } catch (Exception $exception) {
            return $this->apiResponse('Failed to list users!', $exception, 404);
        }
    }
}
