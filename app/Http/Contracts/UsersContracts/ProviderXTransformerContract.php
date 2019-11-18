<?php

namespace App\Http\Contracts\UsersContracts;

interface ProviderXTransformerContract
{

    /**
     * Transform provider y interface.
     *
     * @param array $data
     * @return array
     */
    public function transformProviderX(array $data): array;
}
