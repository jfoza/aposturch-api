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
    public function havePermission(
        string|null $rule = null,
        bool $isModule = false
    ): void
    {
        if(!$this->haveRule($rule)) {
            $this->dispatchForbiddenError($isModule);
        }
    }

    /**
     * @param string|null $rule
     * @return bool
     */
    public function haveRule(string|null $rule = null): bool
    {
        return in_array($rule, $this->rules);
    }

    /**
     * @throws AppException
     */
    public function dispatchForbiddenError(bool $isModule = false)
    {
        throw new AppException(
            $isModule ? MessagesEnum::MODULE_NOT_AUTHORIZED : MessagesEnum::NOT_AUTHORIZED,
            Response::HTTP_FORBIDDEN
        );
    }
}
