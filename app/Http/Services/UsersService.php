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

    private $providers = ['DataProviderX', 'DataProviderY'];
    private $statusCodes = ['authorised', 'decline', 'refunded'];

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
                $provider = $this->filterUsersWithProvider($usersList, $filterInputs);
                $usersList = $provider;
            }

            if (array_key_exists('statusCode', $filterInputs)) {
                $statusCode = $this->filterUsersWithStatusCode($usersList, $filterInputs);
                $usersList = $statusCode;
            }

            if (array_key_exists('currency', $filterInputs)) {
                $currency = $this->filterUsersWithCurrency($usersList, $filterInputs);
                $usersList = $currency;
            }

            if (array_key_exists('balanceMin', $filterInputs) || array_key_exists('balanceMax', $filterInputs)) {
                $amountRange = $this->filterUsersWithAmountRange($usersList, $filterInputs);
                $usersList = $amountRange;
            }

            return $usersList;
        } catch (Exception $exception) {
            return ['message' => 'Failed to filter providers!', 'exception' => $exception];
        }
    }

    /**
     * Filter and validate users with provider.
     *
     * @param array $usersList
     * @param array $filterInputs
     * @return array
     */
    private function filterUsersWithProvider(array $usersList, array $filterInputs): array
    {
        if (in_array($filterInputs['provider'], $this->providers)) {
            return $this->usersRepository->filterUsersByProvider($usersList, $filterInputs);
        } else {
            return ['status' => 'Error', 'message' => 'Invalid provider name input!'];
        }
    }

    /**
     * Filter and validate users with status code.
     *
     * @param array $usersList
     * @param array $filterInputs
     * @return array
     */
    private function filterUsersWithStatusCode(array $usersList, array $filterInputs): array
    {
        if (in_array($filterInputs['statusCode'], $this->statusCodes)) {
            return $this->usersRepository->filterUsersByStatusCode($usersList, $filterInputs);
        } else {
            return ['status' => 'Error', 'message' => 'Invalid status code inputs!'];
        }
    }

    /**
     * Filter and validate users with currency.
     *
     * @param array $usersList
     * @param array $filterInputs
     * @return array
     */
    private function filterUsersWithCurrency(array $usersList, array $filterInputs): array
    {
        if (!empty($filterInputs['currency'])) {
            return $this->usersRepository->filterUsersByCurrency($usersList, $filterInputs);
        } else {
            return ['status' => 'Error', 'message' => 'Invalid currency input!'];
        }
    }

    /**
     * Filter and validate users with amount range.
     *
     * @param array $usersList
     * @param array $filterInputs
     * @return array
     */
    private function filterUsersWithAmountRange(array $usersList, array $filterInputs): array
    {
        if (!empty($filterInputs['balanceMin']) && !empty($filterInputs['balanceMax'])) {
            return $this->usersRepository->filterUsersByAmount($usersList, $filterInputs);
        } else {
            return ['status' => 'Error', 'message' => 'Invalid amount range inputs!'];
        }
    }
}
