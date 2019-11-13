<?php

namespace App\Http\Services;

use App\Http\Repositories\UsersRepository;
use App\Http\Transformers\UsersTransformer;
use Nahid\JsonQ\Exceptions\ConditionNotAllowedException;
use Nahid\JsonQ\Exceptions\NullValueException;

class UsersService
{
    protected $usersRepository;
    protected $usersTransformer;

    private $dataProviderX = 'json/DataProviderX.json';
    private $dataProviderY = 'json/DataProviderY.json';

    /**
     * UsersService constructor.
     * @param UsersRepository $usersRepository
     * @param UsersTransformer $usersTransformer
     */
    public function __construct(UsersRepository $usersRepository, UsersTransformer $usersTransformer)
    {
        $this->usersRepository = $usersRepository;
        $this->usersTransformer = $usersTransformer;
    }

    public function mergeUsersData()
    {
        try {
            $dataProviderX = $this->usersRepository->getDataFromJsonDataProviders($this->dataProviderX);
            $dataProviderY = $this->usersRepository->getDataFromJsonDataProviders($this->dataProviderY);
            return $this->usersTransformer->mergeProviders(['dataProviderX' => $dataProviderX, 'dataProviderY' => $dataProviderY]);
        } catch (ConditionNotAllowedException $exception) {
            report($exception);
            return false;
        } catch (NullValueException $exception) {
            report($exception);
            return false;
        }
    }
}
