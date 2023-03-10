<?php

namespace App\Features\Users\EmailVerification\Providers;

use App\Features\Users\EmailVerification\Contracts\EmailVerificationRepositoryInterface;
use App\Features\Users\EmailVerification\Infra\Repositories\EmailVerificationRepository;
use Illuminate\Support\ServiceProvider;

class EmailVerificationProvider extends ServiceProvider
{
    public array $bindings = [
        EmailVerificationRepositoryInterface::class => EmailVerificationRepository::class,
    ];
}
