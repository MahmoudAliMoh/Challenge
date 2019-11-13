<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Http\Services\UsersService;

class UsersAPI extends Controller
{
    private $userService;

    public function __construct(UsersService $userService)
    {
        $this->userService = $userService;
    }

    public function getUsersList()
    {
        $this->userService->mergeUsersData();
    }
}
