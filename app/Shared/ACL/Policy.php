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
    public function havePermission(string|null $rule = null): void {
        if(!$this->haveRule($rule)) {
            $this->dispatchErrorForbidden();
        }
    }

    /**
     * @throws AppException
     */
    public function havePermissionAndModule(
        string|null $rule = null,
        string|null $moduleRule = null
    ): void {
        if(!$this->haveRuleAndModule($rule, $moduleRule)) {
            $this->dispatchErrorForbidden();
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
     * @param string|null $rule
     * @param string|null $moduleRule
     * @return bool
     */
    public function haveRuleAndModule(
        string|null $rule = null,
        string|null $moduleRule = null
    ): bool
    {
        return in_array($rule, $this->rules) && in_array($moduleRule, $this->rules);
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
