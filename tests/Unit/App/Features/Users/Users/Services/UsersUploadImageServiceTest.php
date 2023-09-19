<?php

namespace Tests\Unit\App\Features\Users\Users\Services;

use App\Exceptions\AppException;
use App\Features\General\Images\Contracts\ImagesRepositoryInterface;
use App\Features\General\Images\DTO\ImagesDTO;
use App\Features\General\Images\Repositories\ImagesRepository;
use App\Features\Module\Modules\Models\Module;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Repositories\UsersRepository;
use App\Features\Users\Users\Services\UserUploadImageService;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Features\Users\Users\UsersDataProvidersTrait;
use Tests\Unit\App\Resources\ImagesLists;
use Tests\Unit\App\Resources\MemberLists;
use Tests\Unit\App\Resources\UsersLists;

class UsersUploadImageServiceTest extends TestCase
{
    use UsersDataProvidersTrait;

    private readonly UsersRepositoryInterface   $usersRepositoryMock;
    private readonly MembersRepositoryInterface $membersRepositoryMock;
    private readonly ImagesRepositoryInterface  $imagesRepositoryMock;
    private readonly MembersFiltersDTO          $membersFiltersDtoMock;

    private MockObject|UploadedFile $uploadedFileMock;
    private MockObject|ImagesDTO    $imagesDtoMock;

    private string $userId;
    private string $churchId;
    private string $moduleId;
    private mixed $churches;
    private mixed $modules;
    private string $imageId;
    private string $imagePath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usersRepositoryMock   = $this->createMock(UsersRepository::class);
        $this->membersRepositoryMock = $this->createMock(MembersRepository::class);
        $this->imagesRepositoryMock  = $this->createMock(ImagesRepository::class);
        $this->membersFiltersDtoMock = $this->createMock(MembersFiltersDTO::class);

        $this->uploadedFileMock = $this->createMock(UploadedFile::class);
        $this->imagesDtoMock    = $this->createMock(ImagesDTO::class);

        $this->userId = Uuid::uuid4Generate();
        $this->churchId = Uuid::uuid4Generate();
        $this->moduleId = Uuid::uuid4Generate();
        $this->imageId = Uuid::uuid4Generate();
        $this->imagePath = 'user-avatar/test.png';

        $this->churches = Collection::make([(object) ([Church::ID => $this->churchId])]);

        $this->modules = Collection::make([(object) ([Module::ID => $this->moduleId])]);
    }

    public function getUsersUploadImageService(): UserUploadImageService
    {
        return new UserUploadImageService(
            $this->membersRepositoryMock,
            $this->usersRepositoryMock,
            $this->imagesRepositoryMock,
        );
    }

    public function populateImagesDTO(): void
    {
        $this->imagesDtoMock->image = $this->uploadedFileMock;
        $this->imagesDtoMock->id = $this->imageId;
    }

    public function test_should_insert_new_user_member_image_by_admin_master()
    {
        $usersUploadImageService = $this->getUsersUploadImageService();

        $usersUploadImageService->setPolicy(
            new Policy([RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_MASTER->value])
        );

        $this->populateImagesDTO();

        $this
            ->usersRepositoryMock
            ->method('findById')
            ->willReturn(UsersLists::showUser());

        $this
            ->uploadedFileMock
            ->method('store')
            ->willReturn($this->imagePath);

        $this
            ->imagesRepositoryMock
            ->method('create')
            ->willReturn(ImagesLists::getImageCreated($this->imageId));

        $image = $usersUploadImageService->execute($this->imagesDtoMock, $this->userId);

        $this->assertIsObject($image);
    }

    /**
     * @dataProvider dataProviderUploadImageDifferentUser
     *
     * @param string $rule
     * @param string $profileUniqueName
     * @return void
     * @throws AppException
     */
    public function test_should_insert_new_user_member_image_by_members(
        string $rule,
        string $profileUniqueName
    ): void
    {
        $usersUploadImageService = $this->getUsersUploadImageService();

        $usersUploadImageService->setPolicy(
            new Policy([$rule])
        );

        $usersUploadImageService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        $this->populateImagesDTO();

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    $this->churches,
                    $this->modules,
                    $profileUniqueName,
                )
            );

        $this
            ->uploadedFileMock
            ->method('store')
            ->willReturn($this->imagePath);

        $this
            ->imagesRepositoryMock
            ->method('create')
            ->willReturn(ImagesLists::getImageCreated($this->imageId));

        $image = $usersUploadImageService->execute($this->imagesDtoMock, $this->userId);

        $this->assertIsObject($image);
    }

    /**
     * @dataProvider dataProviderUploadImageMemberItself
     *
     * @param string $rule
     * @param string $profileUniqueName
     * @return void
     * @throws AppException
     */
    public function test_should_insert_new_user_member_image_by_members_itself(
        string $rule,
        string $profileUniqueName
    ): void
    {
        $usersUploadImageService = $this->getUsersUploadImageService();

        $usersUploadImageService->setPolicy(
            new Policy([$rule])
        );

        $usersUploadImageService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        $this->populateImagesDTO();

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    $this->churches,
                    $this->modules,
                    $profileUniqueName,
                )
            );

        $this
            ->uploadedFileMock
            ->method('store')
            ->willReturn($this->imagePath);

        $this
            ->imagesRepositoryMock
            ->method('create')
            ->willReturn(ImagesLists::getImageCreated($this->imageId));

        $image = $usersUploadImageService->execute($this->imagesDtoMock, $this->userId);

        $this->assertIsObject($image);
    }

    /**
     * @dataProvider dataProviderUploadImageDifferentUser
     *
     * @param string $rule
     * @param string $profileUniqueName
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_user_member_not_exists(
        string $rule,
        string $profileUniqueName,
    ): void
    {
        $usersUploadImageService = $this->getUsersUploadImageService();

        $usersUploadImageService->setPolicy(
            new Policy([$rule])
        );

        $usersUploadImageService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        $this->populateImagesDTO();

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $usersUploadImageService->execute($this->imagesDtoMock, $this->userId);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $usersUploadImageService = $this->getUsersUploadImageService();

        $usersUploadImageService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $usersUploadImageService->execute($this->imagesDtoMock, $this->userId);
    }
}
