<?php

namespace App\Features\Auth\Providers;

use App\Features\Auth\Business\ForgotPasswordBusiness;
use App\Features\Auth\Contracts\ForgotPasswordBusinessInterface;
use App\Features\Auth\Contracts\ForgotPasswordRepositoryInterface;
use App\Features\Users\ForgotPassword\Infra\Repositories\ForgotPasswordRepository;
use Illuminate\Support\ServiceProvider;

class ForgotPasswordBusinessProvider extends ServiceProvider
{
    public array $bindings = [
        ForgotPasswordRepositoryInterface::class => ForgotPasswordRepository::class,
        ForgotPasswordBusinessInterface::class => ForgotPasswordBusiness::class,
    ];

    public function register()
    {}
}
