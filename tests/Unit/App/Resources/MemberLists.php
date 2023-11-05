<?php

namespace Tests\Unit\App\Resources;

use App\Features\General\Images\Enums\TypeUploadImageEnum;
use App\Features\General\Images\Models\Image;
use App\Features\Module\Modules\Models\Module;
use App\Features\Persons\Infra\Models\Person;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Models\User;
use App\Modules\Membership\Members\Enums\MembersDataAliasEnum;
use App\Modules\Membership\Members\Models\Member;
use App\Shared\Libraries\Uuid;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Collection as SupportCollection;

class MemberLists
{
    public static function memberCreated(): object
    {
        return (object) ([
            Member::ID => Uuid::uuid4Generate(),
        ]);
    }

    public static function getMemberDataView(
        Collection|null $churches = null,
        Collection|null $modules = null,
        string $profileUniqueName = null,
        string $userId = null,
    ): object
    {
        if(is_null($userId))
        {
            $userId = Uuid::uuid4Generate();
        }

        return (object) ([
            MembersDataAliasEnum::MEMBER_ID => Uuid::uuid4Generate(),
            MembersDataAliasEnum::USER_ID => $userId,
            MembersDataAliasEnum::PERSON_ID => Uuid::uuid4Generate(),
            MembersDataAliasEnum::NAME => 'test',
            MembersDataAliasEnum::PROFILE_ID => Uuid::uuid4Generate(),
            MembersDataAliasEnum::PROFILE_UNIQUE_NAME => $profileUniqueName,
            MembersDataAliasEnum::EMAIL => 'test@test.com',
            MembersDataAliasEnum::ACTIVE => true,
            MembersDataAliasEnum::PHONE => '5198765217',
            MembersDataAliasEnum::ADDRESS => 'test',
            MembersDataAliasEnum::NUMBER_ADDRESS => 'test',
            MembersDataAliasEnum::COMPLEMENT => '',
            MembersDataAliasEnum::DISTRICT => 'test',
            MembersDataAliasEnum::ZIP_CODE => '00000000',
            MembersDataAliasEnum::CITY_DESCRIPTION => Uuid::uuid4Generate(),
            MembersDataAliasEnum::UF => 'RS',
            'church' => !empty($churches) ? $churches : Collection::make(),
            'user' => (object) ([
                User::ID => $userId,
                User::AVATAR_ID => Uuid::uuid4Generate(),
                'module' => !empty($modules) ? $modules : Collection::make(),
                'image' => (object) ([
                    Image::ID => Uuid::uuid4Generate(),
                    Image::PATH => 'test',
                    Image::TYPE => TypeUploadImageEnum::USER_AVATAR->value
                ]),
            ]),
        ]);
    }

    public static function getMemberUserLogged(
        string $churchId = null,
        string $moduleId = null,
        string $churchUniqueName = null,
        string $userId = null,
    ): object
    {
        if(is_null($churchId))
        {
            $churchId = Uuid::uuid4Generate();
        }

        if(is_null($churchUniqueName))
        {
            $churchUniqueName = 'test-name';
        }

        if(is_null($userId))
        {
            $userId = Uuid::uuid4Generate();
        }

        return (object) ([
            'id'       => $userId,
            'email'    => 'usuario@email.com',
            'avatar_id'   => null,
            'name' => 'Name User',
            'active' => true,
            'profile' => DatabaseCollection::make([
                (object) ([
                    "id" => "7f94247d-38a7-424b-ae7a-bb3262a587b9",
                    "profile_type_id" => "3facf59b-175a-4b08-8a85-e1d6cb5b4b06",
                    "description" => "Admin Church",
                    "unique_name" => "ADMIN_CHURCH",
                    "active" => true,
                    "created_at" => "2022-09-15T23:12:53.905905Z",
                    "updated_at" => "2022-09-15T23:12:53.905905Z",
                ])
            ]),
            'member' => (object) ([
                "id" => "a4fb63a3-6643-4f71-adda-02e54e41e703",
                "user_id" => $userId,
                "member_type_id" => "57cdbad1-2fd5-4b39-b877-bbcacea89506",
                "code" => 1,
                "active" => true,
                "created_at" => "2023-05-03T21:18:51.786747Z",
                "updated_at" => "2023-05-03T21:18:51.786747Z",

                'church' => DatabaseCollection::make([
                    (object) ([
                        "id" => $churchId,
                        "name" => "Igreja Bíblica Viver Caxias",
                        "unique_name" => $churchUniqueName,
                        "phone" => "51999999999",
                        "email" => "ibvcx@gmail.com",
                        "youtube" => "https://www.youtube.com/channel/UCUjfOsd_ZJJb36JbQ9H1sBA",
                        "facebook" => "https://www.facebook.com/igrejabiblicaviver/",
                        "instagram" => "https://www.instagram.com/igrejaviver/",
                        "zip_code" => "95096830",
                        "address" => "Rua Alexandre Peretti",
                        "number_address" => "2815",
                        "complement" => "",
                        "district" => "Charqueadas",
                        "uf" => "RS",
                        "city_id" => "ba91f558-e217-4753-a7ee-404805c1c275",
                        "active" => true,
                        "created_at" => "2023-05-03T21:18:51.731787Z",
                        "updated_at" => "2023-05-03T21:18:51.731787Z",
                    ])
                ]),
            ]),

            'module' => SupportCollection::make([
                (object) ([
                    Module::ID => is_null($moduleId) ? Uuid::uuid4Generate() : $moduleId,
                    Module::MODULE_DESCRIPTION => 'MEMBERSHIP',
                    Module::MODULE_UNIQUE_NAME => 'MEMBERSHIP',
                    Module::ACTIVE => true,
                ])
            ]),
        ]);
    }

    public static function getMembers(): SupportCollection
    {
        return collect([
            [
                MembersDataAliasEnum::MEMBER_ID => Uuid::uuid4Generate(),
                MembersDataAliasEnum::USER_ID => Uuid::uuid4Generate(),
                MembersDataAliasEnum::PERSON_ID => Uuid::uuid4Generate(),
                MembersDataAliasEnum::NAME => 'test',
                MembersDataAliasEnum::PROFILE_ID => Uuid::uuid4Generate(),
                MembersDataAliasEnum::PROFILE_UNIQUE_NAME => ProfileUniqueNameEnum::ASSISTANT->value,
                MembersDataAliasEnum::EMAIL => 'test@test.com',
                MembersDataAliasEnum::ACTIVE => true,
                MembersDataAliasEnum::PHONE => '5198765217',
                MembersDataAliasEnum::ADDRESS => 'test',
                MembersDataAliasEnum::NUMBER_ADDRESS => 'test',
                MembersDataAliasEnum::COMPLEMENT => '',
                MembersDataAliasEnum::DISTRICT => 'test',
                MembersDataAliasEnum::ZIP_CODE => '00000000',
                MembersDataAliasEnum::CITY_DESCRIPTION => Uuid::uuid4Generate(),
                MembersDataAliasEnum::UF => 'RS',
                'church' => []
            ]
        ]);
    }

    public static function getPerson(): object
    {
        return (object) ([
            Person::ID             => Uuid::uuid4Generate(),
            Person::ZIP_CODE       => '00000000',
            Person::PHONE          => '5199999999',
            Person::ADDRESS        => 'test',
            Person::NUMBER_ADDRESS => '00',
            Person::COMPLEMENT     => 'test',
            Person::DISTRICT       => 'test',
            Person::CITY_ID        => Uuid::uuid4Generate(),
            Person::UF             => 'RS',
        ]);
    }
}
