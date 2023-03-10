<?php

namespace App\Features\Base\Infra\Repositories;

use App\Features\Users\ModulesRules\Infra\Models\ModuleRule;
use App\Features\Users\Profiles\Infra\Models\Profile;
use App\Features\Users\ProfilesRules\Infra\Models\ProfileRule;
use App\Features\Users\ProfilesUsers\Infra\Models\ProfileUser;
use App\Features\Users\Rules\Infra\Models\Rule;

class PolicyRepository
{
    protected function findAllByProfileUserId(string $userId)
    {
        return Rule::select(
                Rule::tableField(Rule::DESCRIPTION),
                Rule::tableField(Rule::SUBJECT),
                Rule::tableField(Rule::ACTION),
            )
            ->join(
                ProfileRule::tableName(),
                ProfileRule::tableField(ProfileRule::RULE_ID),
                Rule::tableField(Rule::ID)
            )
            ->join(
                Profile::tableName(),
                Profile::tableField(Profile::ID),
                ProfileRule::tableField(ProfileRule::PROFILE_ID),
            )
            ->join(
                ProfileUser::tableName(),
                ProfileUser::tableField(ProfileUser::PROFILE_ID),
                Profile::tableField(Profile::ID)
            )
            ->where([
                ProfileUser::tableField(ProfileUser::USER_ID) => $userId,
                Profile::tableField(Profile::ACTIVE) => true,
                Rule::tableField(Rule::ACTIVE) => true,
            ]);
    }

    protected function findAllByModulesUser(array $modulesId)
    {
        return Rule::select(
                Rule::tableField(Rule::DESCRIPTION),
                Rule::tableField(Rule::SUBJECT),
                Rule::tableField(Rule::ACTION),
            )
            ->join(
                ModuleRule::tableName(),
                ModuleRule::tableField(ModuleRule::RULE_ID),
                Rule::tableField(Rule::ID)
            )
            ->where(
                Rule::tableField(Rule::ACTIVE),
                true
            )
            ->whereIn(
                ModuleRule::tableField(ModuleRule::MODULE_ID),
                $modulesId
            );
    }

    public function findAllPolicyUser(string $userId, array|null $userModulesId)
    {
        $rulesByProfileUserId = $this->findAllByProfileUserId($userId);

        return $this->findAllByModulesUser($userModulesId)
            ->union($rulesByProfileUserId)
            ->groupBy(Rule::tableField(Rule::ID))
            ->get()
            ->pluck(Rule::DESCRIPTION)
            ->toArray();
    }
}
