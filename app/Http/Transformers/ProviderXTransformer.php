<?php

namespace App\Http\Transformers;


use App\Http\Contracts\UsersContracts\ProviderXTransformerContract;

class ProviderXTransformer implements ProviderXTransformerContract
{
    /**
     * Transform providerX.
     *
     * @param $data
     * @return array
     */
    public function transformProviderX(array $data): array
    {
        $providerData = [];
        foreach ($data as $provider) {
            $providerData[] = [
                'parentAmount' => $provider['parentAmount'],
                'currency' => $provider['Currency'],
                'parentEmail' => $provider['parentEmail'],
                'statusCode' => $provider['statusCode'],
                'registrationDate' => $provider['registerationDate'],
                'parentIdentification' => $provider['parentIdentification'],
                'provider' => 'DataProviderX'
            ];
        }
        return $providerData;
    }
}
