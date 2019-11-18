<?php

namespace App\Http\Requests;

use App\Http\Contracts\UsersContracts\UsersFilterRequestContract;

class UsersRequest implements UsersFilterRequestContract
{
    private $filterInputs = ['provider', 'statusCode', 'currency'];

    /**
     * Validate request.
     *
     * @param $request
     * @return bool
     */
    public function validate($request): bool
    {
        foreach ($request as $key => $item) {
            if (in_array($key, $this->filterInputs)) {
                continue;
            } else {
                return false;
            }
        }
        return true;
    }
}
