<?php

namespace App\Http\Repositories;

use App\Http\Controllers\APIs\Traits\APIsCommonMethods;
use Nahid\JsonQ\Exceptions\FileNotFoundException;
use Nahid\JsonQ\Exceptions\InvalidJsonException;
use Nahid\JsonQ\Jsonq;

class UsersRepository
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
    public function getDataFromJsonDataProviders($providerJsonPath)
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
}
