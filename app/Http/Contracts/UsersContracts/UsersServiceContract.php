<?php

namespace App\Http\Contracts\UsersContracts;

interface UsersServiceContract
{
    /**
     * Get data from provider data interface.
     *
     * @param string $path
     * @return array
     */
    public function getProviderData(string $path): array;

    /**
     * Merge providers array.
     *
     * @param $filterInputs
     * @return array
     */
    public function mergeProviders($filterInputs): array;

    /**
     * Filter users array.
     *
     * @param array $users
     * @param array $filterInputs
     * @return array
     */
    public function filterUsers(array $users, array $filterInputs): array;
}
