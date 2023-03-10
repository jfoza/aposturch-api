<?php

namespace App\Shared\Cache;

use App\Shared\Enums\CacheEnum;
use Closure;
use Illuminate\Support\Facades\Cache;

class PolicyCache
{
    public static function rememberPolicy(string $id, Closure $callback)
    {
        $value = Cache::get(CacheEnum::POLICY->value);

        if(!is_null($value) && isset($value["POLICY_USER_ID_{$id}"])) {
            return $value["POLICY_USER_ID_{$id}"];
        }

        $value["POLICY_USER_ID_{$id}"] = $callback();

        Cache::forever(CacheEnum::POLICY->value, $value);

        return $value["POLICY_USER_ID_{$id}"];
    }

    public static function invalidatePolicy(string $id = null): void
    {
        $value = Cache::get(CacheEnum::POLICY->value);

        if(!is_null($value) && isset($value["POLICY_USER_ID_{$id}"])) {
            unset($value["POLICY_USER_ID_{$id}"]);

            Cache::forever(CacheEnum::POLICY->value, $value);
        }
    }

    public static function invalidateAllPolicy(): void
    {
        Cache::forget(CacheEnum::POLICY->value);
    }
}
