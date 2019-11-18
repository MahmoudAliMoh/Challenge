<?php

namespace App\Http\Contracts\UsersContracts;

interface ProviderYTransformerContract
{

    /**
     * Transform provider y interface.
     *
     * @param array $data
     * @return array
     */
    public function transformProviderY(array $data): array;
}
