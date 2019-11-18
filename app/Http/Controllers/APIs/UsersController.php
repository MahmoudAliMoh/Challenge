<?php

namespace App\Http\Controllers\APIs;

use App\Http\Contracts\UsersContracts\UsersServiceContract;
use App\Http\Controllers\APIs\Traits\APIsCommonMethods;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    use APIsCommonMethods;

    private $userService;

    public function __construct(UsersServiceContract $userService)
    {
        $this->userService = $userService;
    }

    public function getUsersList(Request $request)
    {
        $filterInputs = $request->all();
        $users = $this->userService->mergeProviders($filterInputs);
        return $this->apiResponse('Users list.', $users, 202);
    }
}
