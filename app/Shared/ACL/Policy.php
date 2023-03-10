<?php

namespace App\Shared\ACL;

use App\Shared\Enums\MessagesEnum;
use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

class Policy
{
    private array $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @throws AppException
     */
    public function havePermission(mixed $rule = null): void {
        if(!$this->haveRule($rule)) {
            $this->dispatchErrorForbidden();
        }
    }

    /**
     * @param string|null $rule
     * @return bool
     */
    public function haveRule(mixed $rule = null): bool
    {
        return in_array($rule, $this->rules);
    }

    /**
     * @throws AppException
     */
    public function dispatchErrorForbidden()
    {
        throw new AppException(
            MessagesEnum::NOT_AUTHORIZED,
            Response::HTTP_FORBIDDEN
        );
    }
}
