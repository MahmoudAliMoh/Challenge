<?php

namespace App\Http\Providers;

use App\Http\Contracts\UsersContracts\ProviderXTransformerContract;
use App\Http\Contracts\UsersContracts\ProviderYTransformerContract;
use App\Http\Contracts\UsersContracts\UsersFilterRequestContract;
use App\Http\Contracts\UsersContracts\UsersRepositoryContract;
use App\Http\Contracts\UsersContracts\UsersServiceContract;
use App\Http\Repositories\UsersRepository;
use App\Http\Requests\UsersRequest;
use App\Http\Services\UsersService;
use App\Http\Transformers\ProviderXTransformer;
use App\Http\Transformers\ProviderYTransformer;
use Illuminate\Support\ServiceProvider;

class UsersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->bind(UsersServiceContract::class, UsersService::class);
        $this->app->bind(ProviderXTransformerContract::class, ProviderXTransformer::class);
        $this->app->bind(ProviderYTransformerContract::class, ProviderYTransformer::class);
        $this->app->bind(UsersRepositoryContract::class, UsersRepository::class);
        $this->app->bind(UsersFilterRequestContract::class, UsersRequest::class);
    }
}
