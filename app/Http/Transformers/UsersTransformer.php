<?php

namespace App\Http\Transformers;

class UsersTransformer
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
    public function transformProviderY($data)
    {
        $providerData = [];
        foreach ($data as $provider) {
            $providerData[] = [
                'parentAmount' => $provider['balance'],
                'Currency' => $provider['currency'],
                'parentEmail' => $provider['email'],
                'statusCode' => $this->checkAndUpdateStatusCode($provider['status']),
                'registrationDate' => $provider['created_at'],
                'parentIdentification' => $provider['id'],
                'type' => 'DataProviderY'
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
    private function checkAndUpdateStatusCode($status)
    {
        if ($status == 100) {
            return 1;
        } elseif ($status == 200) {
            return 2;
        } elseif ($status == 300) {
            return 3;
        }
    }
}
