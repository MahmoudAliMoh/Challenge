<?php

namespace App\Http\Contracts\UsersContracts;

interface UsersFilterRequestContract
{
    /**
     * Validate request.
     *
     * @param $request
     * @return bool
     */
    public function validate($request): bool;
}
