<?php

namespace Tests\Feature\Resources\Modules\Members;

use App\Shared\Libraries\Uuid;
use Tests\Feature\App\Features\Auth\Credentials;

trait DataProviders
{
    public static function dataProviderCreateUpdateMembers(): array
    {
        return [
            'By Admin Master' => [Credentials::ADMIN_MASTER],
            'By Admin Church' => [Credentials::ADMIN_CHURCH_1],
            'By Admin Module' => [Credentials::MEMBERSHIP_ADMIN_MODULE],
            'By Assistant'    => [Credentials::ASSISTANT_1],
        ];
    }

    public static function dataProviderCreateUpdateMembersChurchValidations(): array
    {
        return [
            'By Admin Church' => [Credentials::ADMIN_CHURCH_1],
            'By Admin Module' => [Credentials::MEMBERSHIP_ADMIN_MODULE],
            'By Assistant'    => [Credentials::ASSISTANT_1],
        ];
    }

    public static function dataProviderCreateUpdateMembersProfileValidations(): array
    {
        return [
            'By Admin Module' => [Credentials::MEMBERSHIP_ADMIN_MODULE],
            'By Assistant'    => [Credentials::ASSISTANT_1],
        ];
    }

    public static function dataProviderUpdateChurchProfilesAndModules(): array
    {
        return [
            'By Admin Master' => [Credentials::ADMIN_MASTER],
            'By Admin Church' => [Credentials::ADMIN_CHURCH_1],
        ];
    }

    public static function dataProviderUpdateFromAdminChurch(): array
    {
        return [
            'By Admin Church' => [Credentials::ADMIN_CHURCH_1],
            'By Admin Module' => [Credentials::MEMBERSHIP_ADMIN_MODULE],
            'By Assistant'    => [Credentials::ASSISTANT_1],
        ];
    }

    public static function dataProviderFormErrors(): array
    {
        return [
            'Empty name' => [
                'name'                 => '',
                'email'                => 'test@email.com',
                'password'             => 'Teste123',
                'passwordConfirmation' => 'Teste123',
                'profileId'            => Uuid::uuid4Generate(),
                'modulesId'            => [Uuid::uuid4Generate()],
                'churchId'             => Uuid::uuid4Generate(),
                'cityId'               => Uuid::uuid4Generate(),
                'phone'                => '51999999999',
                'zipCode'              => '99999999',
                'address'              => 'teste',
                'numberAddress'        => '23',
                'complement'           => '',
                'district'             => 'teste',
                'uf'                   => 'RS'
            ],
            'Invalid name' => [
                'name'                 => '#@$Test',
                'email'                => 'test@email.com',
                'password'             => 'Teste123',
                'passwordConfirmation' => 'Teste123',
                'profileId'            => Uuid::uuid4Generate(),
                'modulesId'            => [Uuid::uuid4Generate()],
                'churchId'             => Uuid::uuid4Generate(),
                'cityId'               => Uuid::uuid4Generate(),
                'phone'                => '51999999999',
                'zipCode'              => '99999999',
                'address'              => 'teste',
                'numberAddress'        => '23',
                'complement'           => '',
                'district'             => 'teste',
                'uf'                   => 'RS'
            ],
            'Empty email' => [
                'name'                 => 'test',
                'email'                => '',
                'password'             => 'Teste123',
                'passwordConfirmation' => 'Teste123',
                'profileId'            => Uuid::uuid4Generate(),
                'modulesId'            => [Uuid::uuid4Generate()],
                'churchId'             => Uuid::uuid4Generate(),
                'cityId'               => Uuid::uuid4Generate(),
                'phone'                => '51999999999',
                'zipCode'              => '99999999',
                'address'              => 'teste',
                'numberAddress'        => '23',
                'complement'           => '',
                'district'             => 'teste',
                'uf'                   => 'RS'
            ],
            'Invalid email' => [
                'name'                 => 'test',
                'email'                => 'invalid@',
                'password'             => 'Teste123',
                'passwordConfirmation' => 'Teste123',
                'profileId'            => Uuid::uuid4Generate(),
                'modulesId'            => [Uuid::uuid4Generate()],
                'churchId'             => Uuid::uuid4Generate(),
                'cityId'               => Uuid::uuid4Generate(),
                'phone'                => '51999999999',
                'zipCode'              => '99999999',
                'address'              => 'teste',
                'numberAddress'        => '23',
                'complement'           => '',
                'district'             => 'teste',
                'uf'                   => 'RS'
            ],
            'Empty password' => [
                'name'                 => 'test',
                'email'                => 'test@email.com',
                'password'             => '',
                'passwordConfirmation' => 'Teste123',
                'profileId'            => Uuid::uuid4Generate(),
                'modulesId'            => [Uuid::uuid4Generate()],
                'churchId'             => Uuid::uuid4Generate(),
                'cityId'               => Uuid::uuid4Generate(),
                'phone'                => '51999999999',
                'zipCode'              => '99999999',
                'address'              => 'teste',
                'numberAddress'        => '23',
                'complement'           => '',
                'district'             => 'teste',
                'uf'                   => 'RS'
            ],
            'Empty password confirmation' => [
                'name'                 => 'test',
                'email'                => 'test@email.com',
                'password'             => 'Teste123',
                'passwordConfirmation' => '',
                'profileId'            => Uuid::uuid4Generate(),
                'modulesId'            => [Uuid::uuid4Generate()],
                'churchId'             => Uuid::uuid4Generate(),
                'cityId'               => Uuid::uuid4Generate(),
                'phone'                => '51999999999',
                'zipCode'              => '99999999',
                'address'              => 'teste',
                'numberAddress'        => '23',
                'complement'           => '',
                'district'             => 'teste',
                'uf'                   => 'RS'
            ],
            'Different passwords' => [
                'name'                 => 'test',
                'email'                => 'test@email.com',
                'password'             => 'Teste123',
                'passwordConfirmation' => 'Teste1234',
                'profileId'            => Uuid::uuid4Generate(),
                'modulesId'            => [Uuid::uuid4Generate()],
                'churchId'             => Uuid::uuid4Generate(),
                'cityId'               => Uuid::uuid4Generate(),
                'phone'                => '51999999999',
                'zipCode'              => '99999999',
                'address'              => 'teste',
                'numberAddress'        => '23',
                'complement'           => '',
                'district'             => 'teste',
                'uf'                   => 'RS'
            ],

            'Empty profile id' => [
                'name'                 => 'test',
                'email'                => 'test@email.com',
                'password'             => 'Teste123',
                'passwordConfirmation' => 'Teste123',
                'profileId'            => '',
                'modulesId'            => [Uuid::uuid4Generate()],
                'churchId'             => Uuid::uuid4Generate(),
                'cityId'               => Uuid::uuid4Generate(),
                'phone'                => '51999999999',
                'zipCode'              => '99999999',
                'address'              => 'teste',
                'numberAddress'        => '23',
                'complement'           => '',
                'district'             => 'teste',
                'uf'                   => 'RS'
            ],
            'Empty modules id' => [
                'name'                 => 'test',
                'email'                => 'test@email.com',
                'password'             => 'Teste123',
                'passwordConfirmation' => 'Teste123',
                'profileId'            => Uuid::uuid4Generate(),
                'modulesId'            => [],
                'churchId'             => Uuid::uuid4Generate(),
                'cityId'               => Uuid::uuid4Generate(),
                'phone'                => '51999999999',
                'zipCode'              => '99999999',
                'address'              => 'teste',
                'numberAddress'        => '23',
                'complement'           => '',
                'district'             => 'teste',
                'uf'                   => 'RS'
            ],
            'Empty church id' => [
                'name'                 => 'test',
                'email'                => 'test@email.com',
                'password'             => 'Teste123',
                'passwordConfirmation' => 'Teste123',
                'profileId'            => Uuid::uuid4Generate(),
                'modulesId'            => [Uuid::uuid4Generate()],
                'churchId'             => '',
                'cityId'               => Uuid::uuid4Generate(),
                'phone'                => '51999999999',
                'zipCode'              => '99999999',
                'address'              => 'teste',
                'numberAddress'        => '23',
                'complement'           => '',
                'district'             => 'teste',
                'uf'                   => 'RS'
            ],
            'Empty city id' => [
                'name'                 => 'test',
                'email'                => 'test@email.com',
                'password'             => 'Teste123',
                'passwordConfirmation' => 'Teste123',
                'profileId'            => Uuid::uuid4Generate(),
                'modulesId'            => [Uuid::uuid4Generate()],
                'churchId'             => Uuid::uuid4Generate(),
                'cityId'               => '',
                'phone'                => '51999999999',
                'zipCode'              => '99999999',
                'address'              => 'teste',
                'numberAddress'        => '23',
                'complement'           => '',
                'district'             => 'teste',
                'uf'                   => 'RS'
            ],
            'Invalid profile id' => [
                'name'                 => 'test',
                'email'                => 'test@email.com',
                'password'             => 'Teste123',
                'passwordConfirmation' => 'Teste123',
                'profileId'            => 'invalid-uuid',
                'modulesId'            => [Uuid::uuid4Generate()],
                'churchId'             => Uuid::uuid4Generate(),
                'cityId'               => Uuid::uuid4Generate(),
                'phone'                => '51999999999',
                'zipCode'              => '99999999',
                'address'              => 'teste',
                'numberAddress'        => '23',
                'complement'           => '',
                'district'             => 'teste',
                'uf'                   => 'RS'
            ],
            'Invalid modules id' => [
                'name'                 => 'test',
                'email'                => 'test@email.com',
                'password'             => 'Teste123',
                'passwordConfirmation' => 'Teste123',
                'profileId'            => Uuid::uuid4Generate(),
                'modulesId'            => ['invalid-uuid'],
                'churchId'             => Uuid::uuid4Generate(),
                'cityId'               => Uuid::uuid4Generate(),
                'phone'                => '51999999999',
                'zipCode'              => '99999999',
                'address'              => 'teste',
                'numberAddress'        => '23',
                'complement'           => '',
                'district'             => 'teste',
                'uf'                   => 'RS'
            ],
            'Invalid church id' => [
                'name'                 => 'test',
                'email'                => 'test@email.com',
                'password'             => 'Teste123',
                'passwordConfirmation' => 'Teste123',
                'profileId'            => Uuid::uuid4Generate(),
                'modulesId'            => [Uuid::uuid4Generate()],
                'churchId'             => 'invalid-uuid',
                'cityId'               => Uuid::uuid4Generate(),
                'phone'                => '51999999999',
                'zipCode'              => '99999999',
                'address'              => 'teste',
                'numberAddress'        => '23',
                'complement'           => '',
                'district'             => 'teste',
                'uf'                   => 'RS'
            ],
            'Invalid city id' => [
                'name'                 => 'test',
                'email'                => 'test@email.com',
                'password'             => 'Teste123',
                'passwordConfirmation' => 'Teste123',
                'profileId'            => Uuid::uuid4Generate(),
                'modulesId'            => [Uuid::uuid4Generate()],
                'churchId'             => Uuid::uuid4Generate(),
                'cityId'               => 'invalid-uuid',
                'phone'                => '51999999999',
                'zipCode'              => '99999999',
                'address'              => 'teste',
                'numberAddress'        => '23',
                'complement'           => '',
                'district'             => 'teste',
                'uf'                   => 'RS'
            ],
            'Empty phone' => [
                'name'                 => 'test',
                'email'                => 'test@email.com',
                'password'             => 'Teste123',
                'passwordConfirmation' => 'Teste123',
                'profileId'            => Uuid::uuid4Generate(),
                'modulesId'            => [Uuid::uuid4Generate()],
                'churchId'             => Uuid::uuid4Generate(),
                'cityId'               => Uuid::uuid4Generate(),
                'phone'                => '',
                'zipCode'              => '99999999',
                'address'              => 'teste',
                'numberAddress'        => '23',
                'complement'           => '',
                'district'             => 'teste',
                'uf'                   => 'RS'
            ],
            'Empty zip code' => [
                'name'                 => 'test',
                'email'                => 'test@email.com',
                'password'             => 'Teste123',
                'passwordConfirmation' => 'Teste123',
                'profileId'            => Uuid::uuid4Generate(),
                'modulesId'            => [Uuid::uuid4Generate()],
                'churchId'             => Uuid::uuid4Generate(),
                'cityId'               => Uuid::uuid4Generate(),
                'phone'                => '51999999999',
                'zipCode'              => '',
                'address'              => 'teste',
                'numberAddress'        => '23',
                'complement'           => '',
                'district'             => 'teste',
                'uf'                   => 'RS'
            ],
            'Empty address' => [
                'name'                 => 'test',
                'email'                => 'test@email.com',
                'password'             => 'Teste123',
                'passwordConfirmation' => 'Teste123',
                'profileId'            => Uuid::uuid4Generate(),
                'modulesId'            => [Uuid::uuid4Generate()],
                'churchId'             => Uuid::uuid4Generate(),
                'cityId'               => Uuid::uuid4Generate(),
                'phone'                => '51999999999',
                'zipCode'              => '99999999',
                'address'              => '',
                'numberAddress'        => '23',
                'complement'           => '',
                'district'             => 'teste',
                'uf'                   => 'RS'
            ],
            'Empty number' => [
                'name'                 => 'test',
                'email'                => 'test@email.com',
                'password'             => 'Teste123',
                'passwordConfirmation' => 'Teste123',
                'profileId'            => Uuid::uuid4Generate(),
                'modulesId'            => [Uuid::uuid4Generate()],
                'churchId'             => Uuid::uuid4Generate(),
                'cityId'               => Uuid::uuid4Generate(),
                'phone'                => '51999999999',
                'zipCode'              => '99999999',
                'address'              => 'test',
                'numberAddress'        => '',
                'complement'           => '',
                'district'             => 'teste',
                'uf'                   => 'RS'
            ],
            'Empty district' => [
                'name'                 => 'test',
                'email'                => 'test@email.com',
                'password'             => 'Teste123',
                'passwordConfirmation' => 'Teste123',
                'profileId'            => Uuid::uuid4Generate(),
                'modulesId'            => [Uuid::uuid4Generate()],
                'churchId'             => Uuid::uuid4Generate(),
                'cityId'               => Uuid::uuid4Generate(),
                'phone'                => '51999999999',
                'zipCode'              => '99999999',
                'address'              => 'test',
                'numberAddress'        => '00',
                'complement'           => '',
                'district'             => '',
                'uf'                   => 'RS'
            ],
            'Empty uf' => [
                'name'                 => 'test',
                'email'                => 'test@email.com',
                'password'             => 'Teste123',
                'passwordConfirmation' => 'Teste123',
                'profileId'            => Uuid::uuid4Generate(),
                'modulesId'            => [Uuid::uuid4Generate()],
                'churchId'             => Uuid::uuid4Generate(),
                'cityId'               => Uuid::uuid4Generate(),
                'phone'                => '51999999999',
                'zipCode'              => '99999999',
                'address'              => 'test',
                'numberAddress'        => '00',
                'complement'           => '',
                'district'             => 'test',
                'uf'                   => ''
            ],
            'Invalid uf' => [
                'name'                 => 'test',
                'email'                => 'test@email.com',
                'password'             => 'Teste123',
                'passwordConfirmation' => 'Teste123',
                'profileId'            => Uuid::uuid4Generate(),
                'modulesId'            => [Uuid::uuid4Generate()],
                'churchId'             => Uuid::uuid4Generate(),
                'cityId'               => Uuid::uuid4Generate(),
                'phone'                => '51999999999',
                'zipCode'              => '99999999',
                'address'              => 'test',
                'numberAddress'        => '00',
                'complement'           => '',
                'district'             => 'test',
                'uf'                   => 'abc'
            ],
        ];
    }

    public static function dataProviderFormErrorsAddressUpdate(): array
    {
        return [
            'Empty zip code' => [
                'zipCode'       => '',
                'address'       => 'Quadra L Cinco',
                'numberAddress' => '30',
                'complement'    => 'casa',
                'district'      => 'Guajuviras',
                'cityId'        => Uuid::uuid4Generate(),
                'uf'            => 'RS',
            ],
            'Empty address' => [
                'zipCode'       => '92440504',
                'address'       => '',
                'numberAddress' => '30',
                'complement'    => 'casa',
                'district'      => 'Guajuviras',
                'cityId'        => Uuid::uuid4Generate(),
                'uf'            => 'RS',
            ],
            'Empty number address' => [
                'zipCode'       => '92440504',
                'address'       => 'Quadra L Cinco',
                'numberAddress' => '',
                'complement'    => 'casa',
                'district'      => 'Guajuviras',
                'cityId'        => Uuid::uuid4Generate(),
                'uf'            => 'RS',
            ],
            'Empty district' => [
                'zipCode'       => '92440504',
                'address'       => 'Quadra L Cinco',
                'numberAddress' => '30',
                'complement'    => 'casa',
                'district'      => '',
                'cityId'        => Uuid::uuid4Generate(),
                'uf'            => 'RS',
            ],
            'Empty city id' => [
                'zipCode'       => '92440504',
                'address'       => 'Quadra L Cinco',
                'numberAddress' => '30',
                'complement'    => 'casa',
                'district'      => 'Guajuviras',
                'cityId'        => '',
                'uf'            => 'RS',
            ],
            'Invalid city id' => [
                'zipCode'       => '92440504',
                'address'       => 'Quadra L Cinco',
                'numberAddress' => '30',
                'complement'    => 'casa',
                'district'      => 'Guajuviras',
                'cityId'        => 'invalid-uuid',
                'uf'            => 'RS',
            ],
            'Empty uf' => [
                'zipCode'       => '92440504',
                'address'       => 'Quadra L Cinco',
                'numberAddress' => '30',
                'complement'    => 'casa',
                'district'      => 'Guajuviras',
                'cityId'        => Uuid::uuid4Generate(),
                'uf'            => '',
            ],
            'Invalid uf' => [
                'zipCode'       => '92440504',
                'address'       => 'Quadra L Cinco',
                'numberAddress' => '30',
                'complement'    => 'casa',
                'district'      => 'Guajuviras',
                'cityId'        => Uuid::uuid4Generate(),
                'uf'            => 'AbC',
            ],
        ];
    }

    public static function dataProviderFormErrorsChurchUpdate(): array
    {
        return [
            'Empty church id'   => ['churchId' => ''],
            'Invalid church id' => ['churchId' => 'invalid-uuid'],
        ];
    }

    public static function dataProviderFormErrorsModulesUpdate(): array
    {
        return [
            'Empty modules array' => ['modulesId' => []],
            'Empty module id'     => ['modulesId' => ['']],
            'Invalid module id'   => ['modulesId' => ['invalid-uuid']],
            'Invalid type'        => ['modulesId' => Uuid::uuid4Generate()],
        ];
    }

    public static function dataProviderFormErrorsProfileUpdate(): array
    {
        return [
            'Empty profile id'   => ['profileId' => ''],
            'Invalid profile id' => ['profileId' => 'invalid-uuid'],
        ];
    }

    public static function dataProviderFormErrorsPasswordUpdate(): array
    {
        return [
            'Empty password'   => [
                "password"             => '',
                "passwordConfirmation" => 'Teste123',
            ],
            'Empty password confirmation'   => [
                "password"             => 'Teste123',
                "passwordConfirmation" => '',
            ],
            'Different passwords'   => [
                "password"             => 'Teste123',
                "passwordConfirmation" => 'Teste1234',
            ],
        ];
    }

    public static function dataProviderFormErrorsGeneralDataUpdate(): array
    {
        return [
            'Empty name' => [
                'name'  => '',
                'email' => Credentials::ASSISTANT_1,
                'phone' => '51999999999',
            ],
            'Invalid name' => [
                'name'  => '#$@Test',
                'email' => Credentials::ASSISTANT_1,
                'phone' => '51999999999',
            ],
            'Empty email' => [
                'name'  => 'Usuario auxiliar 1',
                'email' => '',
                'phone' => '51999999999',
            ],
            'Invalid email' => [
                'name'  => "Usuario auxiliar 1",
                'email' => 'invalid-email@',
                'phone' => '51999999999',
            ],
            'Empty phone' => [
                'name'  => "Usuario auxiliar 1",
                'email' => Credentials::ASSISTANT_1,
                'phone' => '',
            ],
        ];
    }
}
