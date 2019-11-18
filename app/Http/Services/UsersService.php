<?php

namespace App\Http\Services;

use App\Http\Contracts\UsersContracts\ProviderXTransformerContract;
use App\Http\Contracts\UsersContracts\ProviderYTransformerContract;
use App\Http\Contracts\UsersContracts\UsersFilterRequestContract;
use App\Http\Contracts\UsersContracts\UsersRepositoryContract;
use App\Http\Contracts\UsersContracts\UsersServiceContract;
use Mockery\Exception;
use Nahid\JsonQ\Exceptions\ConditionNotAllowedException;
use Nahid\JsonQ\Exceptions\NullValueException;

class UsersService implements UsersServiceContract
{
    protected $usersRepository;
    protected $providerYTransformer;
    protected $providerXTransformer;
    protected $usersRequest;

    private $dataProviderX = 'json/DataProviderX.json';
    private $dataProviderY = 'json/DataProviderY.json';


    /**
     * UsersService constructor.
     * @param UsersRepositoryContract $usersRepository
     * @param ProviderYTransformerContract $providerYTransformer
     * @param ProviderXTransformerContract $providerXTransformer
     * @param UsersFilterRequestContract $usersRequest
     */
    public function __construct(
        UsersRepositoryContract $usersRepository,
        ProviderYTransformerContract $providerYTransformer,
        ProviderXTransformerContract $providerXTransformer,
        UsersFilterRequestContract $usersRequest
    )
    {
        $this->usersRepository = $usersRepository;
        $this->providerYTransformer = $providerYTransformer;
        $this->providerXTransformer = $providerXTransformer;
        $this->usersRequest = $usersRequest;
    }

    /**
     * Get data from provider y path.
     *
     * @param string $path
     * @return array
     */
    public function getProviderData(string $path): array
    {
        try {
            return $this->usersRepository->getDataFromJsonDataProviders($path);
        } catch (ConditionNotAllowedException $exception) {
            report($exception);
            return ['message' => 'Condition not allowed!', 'exception' => $exception];
        } catch (NullValueException $exception) {
            report($exception);
            return ['message' => 'Null value returned!', 'exception' => $exception];
        }
    }

    /**
     * Merge providers array.
     *
     * @param $filerInputs
     * @return array
     */
    public function mergeProviders($filerInputs): array
    {
        try {
            if ($this->usersRequest->validate($filerInputs) == true) {
                $providerX = $this->getProviderData($this->dataProviderX);
                $providerXTransformed = $this->providerXTransformer->transformProviderX($providerX);
                $providerY = $this->getProviderData($this->dataProviderY);
                $providerYTransformed = $this->providerYTransformer->transformProviderY($providerY);

                $mergedProviders = array_merge($providerXTransformed, $providerYTransformed);
                return $this->filterUsers($mergedProviders, $filerInputs);
            } else {
                return ['status' => 'Error', 'message' => 'Invalid filter inputs'];
            }
        } catch (Exception $exception) {
            return ['message' => 'Failed to merge providers!', 'exception' => $exception];
        }
    }

    /**
     * Filter users array.
     *
     * @param array $filterInputs
     * @param array $users
     * @return array
     */
    public function filterUsers(array $users, array $filterInputs): array
    {
        try {
            $usersList = $users;
            if (array_key_exists('provider', $filterInputs)) {
                $filteredByProvider = $this->usersRepository->filterUsersByProvider($usersList, $filterInputs);
                $usersList = $filteredByProvider;
            }

            if (array_key_exists('statusCode', $filterInputs)) {
                $filteredByStatusCode = $this->usersRepository->filterUsersByStatusCode($usersList, $filterInputs);
                $usersList = $filteredByStatusCode;
            }

            if (array_key_exists('currency', $filterInputs)) {
                $filteredByCurrency = $this->usersRepository->filterUsersByCurrency($usersList, $filterInputs);
                $usersList = $filteredByCurrency;
            }

            if (array_key_exists('balanceMin', $filterInputs) || array_key_exists('balanceMax', $filterInputs)) {
                $filteredByAmount = $this->usersRepository->filterUsersByAmount($usersList, $filterInputs);
                $usersList = $filteredByAmount;
            }

            return $usersList;
        } catch (Exception $exception) {
            return ['message' => 'Failed to filter providers!', 'exception' => $exception];
        }
    }
}
