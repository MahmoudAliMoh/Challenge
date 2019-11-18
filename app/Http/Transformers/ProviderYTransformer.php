<?php

namespace App\Http\Transformers;

use App\Http\Contracts\UsersContracts\ProviderYTransformerContract;

class ProviderYTransformer implements ProviderYTransformerContract
{
    /**
     * Merge providers in one array to filter.
     *
     * @param $data
     * @return array
     */
    public function mergeProviders($data)
    {
        $providerY = $this->transformProviderY($data['dataProviderY']);
        $mergedProviders = array_merge($data['dataProviderX'], $providerY);
        return $mergedProviders;
    }

    /**
     * Transform providerY with the same structure of providerX.
     *
     * @param $data
     * @return array
     */
    public function transformProviderY(array $data): array
    {
        $providerData = [];
        foreach ($data as $provider) {
            $providerData[] = [
                'parentAmount' => $provider['balance'],
                'currency' => $provider['currency'],
                'parentEmail' => $provider['email'],
                'statusCode' => $this->checkAndUpdateStatusCode($provider['status']),
                'registrationDate' => $provider['created_at'],
                'parentIdentification' => $provider['id'],
                'provider' => 'DataProviderY'
            ];
        }
        return $providerData;
    }

    /**
     * Check and update status codes as providerX.
     *
     * @param $status
     * @return int
     */
    private function checkAndUpdateStatusCode(int $status): int
    {
        if ($status == 100) {
            return 1;
        } elseif
        ($status == 200) {
            return 2;
        } elseif ($status == 300) {
            return 3;
        }
    }
}
