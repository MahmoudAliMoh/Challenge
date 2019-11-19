<?php

namespace App\Http\Repositories;

use App\Http\Contracts\UsersContracts\UsersRepositoryContract;
use App\Http\Controllers\APIs\Traits\APIsCommonMethods;
use Nahid\JsonQ\Exceptions\FileNotFoundException;
use Nahid\JsonQ\Exceptions\InvalidJsonException;
use Nahid\JsonQ\Jsonq;

class UsersRepository implements UsersRepositoryContract
{
    use APIsCommonMethods;

    /**
     * Get data from json files.
     *
     * @param $providerJsonPath
     * @return \App\Http\Controllers\APIs\Traits\response|array|object
     * @throws \Nahid\JsonQ\Exceptions\ConditionNotAllowedException
     * @throws \Nahid\JsonQ\Exceptions\NullValueException
     */
    public function getDataFromJsonDataProviders($providerJsonPath): array
    {
        try {
            $json = new Jsonq(storage_path($providerJsonPath));
            return $json->from('users')->get();
        } catch (FileNotFoundException $exception) {
            return $this->apiResponse('File not found!', $exception, 404);
        } catch (InvalidJsonException $exception) {
            return $this->apiResponse('Invalid json!', $exception, 400);
        }
    }

    /**
     * Filter users by provider name.
     *
     * @param array $users
     * @param array $filterInputs
     * @return array
     */
    public function filterUsersByProvider(array $users, array $filterInputs): array
    {
        $filteredUsers = [];
        foreach ($users as $user) {
            if ($user['provider'] == $filterInputs['provider']) {
                array_push($filteredUsers, $user);
            }
        }
        return $filteredUsers;
    }

    /**
     * Filter users by status code.
     *
     * @param array $users
     * @param array $filterInputs
     * @return array
     */
    public function filterUsersByStatusCode(array $users, array $filterInputs): array
    {
        $filteredUsers = [];
        foreach ($users as $user) {
            if ($user['statusCode'] == $this->convertStatusCodes($filterInputs['statusCode'])) {
                array_push($filteredUsers, $user);
            }
        }
        return $filteredUsers;
    }

    /**
     * Convert status codes.
     *
     * @param $statusCodes
     * @return int
     */
    private function convertStatusCodes($statusCodes)
    {
        if ($statusCodes == 'authorised') {
            return 1;
        } else if ($statusCodes == 'decline') {
            return 2;
        } else if ($statusCodes == 'refunded') {
            return 3;
        }
    }

    /**
     * Filter users by currency.
     *
     * @param array $users
     * @param array $filterInputs
     * @return array
     */
    public function filterUsersByCurrency(array $users, array $filterInputs): array
    {
        $filteredUsers = [];
        foreach ($users as $user) {
            if ($user['currency'] == $filterInputs['currency']) {
                array_push($filteredUsers, $user);
            }
        }
        return $filteredUsers;
    }

    /**
     * Filter users by amount range.
     *
     * @param array $users
     * @param array $filterInputs
     * @return array
     */
    public function filterUsersByAmount(array $users, array $filterInputs): array
    {
        $filteredUsers = [];
        foreach ($users as $user) {
            if ($user['parentAmount'] >= $filterInputs['balanceMin'] && $user['parentAmount'] <= $filterInputs['balanceMax']) {
                array_push($filteredUsers, $user);
            }
        }
        return $filteredUsers;
    }
}
