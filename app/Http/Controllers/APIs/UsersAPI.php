<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\APIs\Traits\APIsCommonMethods;
use App\Http\Controllers\Controller;
use App\Http\Services\UsersService;

class UsersAPI extends Controller
{
    use APIsCommonMethods;

    private $userService;

    public function __construct(UsersService $userService)
    {
        $this->userService = $userService;
    }

    public function getUsersList()
    {
        $users = $this->userService->mergeUsersData();
        return $this->apiResponse('Users list.', $users, 202);
    }
}
