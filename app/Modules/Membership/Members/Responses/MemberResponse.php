<?php

namespace App\Modules\Membership\Members\Responses;

use App\Shared\Helpers\Helpers;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;

class MemberResponse
{
    public ?string $userId;
    public ?string $name;
    public ?string $email;
    public ?string $phone;
    public ?bool   $active;
    public ?string $zipCode;
    public ?string $address;
    public ?string $numberAddress;
    public ?string $complement;
    public ?string $district;
    public ?string $cityId;
    public ?string $cityDescription;
    public ?string $uf;
    public ?string $profileId;
    public ?string $profileDescription;
    public ?object $church;
    public ?array $image = null;
    public DatabaseCollection $modules;

    public function setMemberResponse(object $member): void
    {
        if($image = !empty($member->user->image) ? $member->user->image : null) {
            $this->image['id']   = $image->id;
            $this->image['type'] = $image->type;
            $this->image['path'] = Helpers::getApiUrl("storage/{$image->path}");
        }

        $this->userId             = $member->user_id;
        $this->name               = $member->name;
        $this->email              = $member->email;
        $this->phone              = $member->phone;
        $this->active             = $member->active;
        $this->zipCode            = $member->zip_code;
        $this->address            = $member->address;
        $this->numberAddress      = $member->number_address;
        $this->complement         = $member->complement;
        $this->district           = $member->district;
        $this->cityId             = $member->city_id;
        $this->cityDescription    = $member->city_description;
        $this->uf                 = $member->uf;
        $this->profileId          = $member->profile_id;
        $this->profileDescription = $member->profile_description;
        $this->church             = collect($member->church)->first();
        $this->modules            = !empty($member->user->module) ? $member->user->module : [];
    }
}
