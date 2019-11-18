<?php

namespace App\Http\Contracts\UsersContracts;

interface UsersRepositoryContract
{
    /**
     * Read data from provider json file.
     *
     * @param string $path
     * @return array
     */
    public function getDataFromJsonDataProviders(string $path): array;

    /**
     * Filter users by provider name.
     *
     * @param array $users
     * @param array $filter
     * @return array
     */
    public function filterUsersByProvider(array $users, array $filter): array;

    /**
     * Filter users by status code.
     *
     * @param array $users
     * @param array $filterInputs
     * @return array
     */
    public function filterUsersByStatusCode(array $users, array $filterInputs): array;

    /**
     * Filter users by currency.
     *
     * @param array $users
     * @param array $filterInputs
     * @return array
     */
    public function filterUsersByCurrency(array $users, array $filterInputs): array;
}
