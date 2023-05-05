<?php

namespace Tests\Unit\App\Modules\Membership\Church\Services;

use App\Exceptions\AppException;
use App\Features\General\Images\Contracts\ImagesRepositoryInterface;
use App\Features\General\Images\DTO\ImagesDTO;
use App\Features\General\Images\Repositories\ImagesRepository;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Repositories\ChurchRepository;
use App\Modules\Membership\Church\Services\ChurchUploadImageService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\ChurchLists;
use Tests\Unit\App\Resources\MembersLists;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ChurchUploadImageServiceTest extends TestCase
{
    private readonly ChurchRepositoryInterface $churchRepositoryMock;
    private readonly ImagesRepositoryInterface $imagesRepositoryMock;

    private MockObject|UploadedFile $uploadedFileMock;
    private MockObject|ImagesDTO    $imagesDtoMock;

    private string $churchId;
    private string $imageId;
    private string $imagePath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->churchRepositoryMock = $this->createMock(ChurchRepository::class);
        $this->imagesRepositoryMock = $this->createMock(ImagesRepository::class);

        $this->uploadedFileMock = $this->createMock(UploadedFile::class);
        $this->imagesDtoMock    = $this->createMock(ImagesDTO::class);

        $this->churchId = Uuid::uuid4()->toString();
        $this->imageId = Uuid::uuid4()->toString();
        $this->imagePath = 'product/test.png';

        JWTAuth::shouldReceive('user')->andreturn(MembersLists::getMemberUserLogged($this->churchId));
        Auth::shouldReceive('user')->andreturn(MembersLists::getMemberUserLogged($this->churchId));
    }

    public function getChurchUploadImageService(): ChurchUploadImageService
    {
        return new ChurchUploadImageService(
            $this->churchRepositoryMock,
            $this->imagesRepositoryMock
        );
    }

    public function populateImagesDTO()
    {
        $this->imagesDtoMock->image = $this->uploadedFileMock;
        $this->imagesDtoMock->id = $this->imageId;
    }

    public function dataProviderUploadImage(): array
    {
        return [
            'By Admin Master Rule' => [RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_IMAGE_UPLOAD->value],
            'By Admin Church Rule' => [RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_IMAGE_UPLOAD->value],
        ];
    }

    /**
     * @dataProvider dataProviderUploadImage
     *
     * @param string $rule
     * @return void
     * @throws AppException|UserNotDefinedException
     */
    public function test_should_insert_new_church_image(string $rule): void
    {
        $churchUploadImageService = $this->getChurchUploadImageService();

        $churchUploadImageService->setPolicy(
            new Policy([$rule])
        );

        $this->populateImagesDTO();

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurch($this->churchId));

        $this
            ->uploadedFileMock
            ->method('store')
            ->willReturn($this->imagePath);

        $this
            ->imagesRepositoryMock
            ->method('create')
            ->willReturn(ChurchLists::getImageCreated($this->imageId));

        $image = $churchUploadImageService->execute($this->imagesDtoMock, $this->churchId);

        $this->assertIsObject($image);
    }

    /**
     * @dataProvider dataProviderUploadImage
     *
     * @param string $rule
     * @return void
     * @throws AppException|UserNotDefinedException
     */
    public function test_should_and_replace_images(string $rule): void
    {
        $churchUploadImageService = $this->getChurchUploadImageService();

        $churchUploadImageService->setPolicy(
            new Policy([$rule])
        );

        $this->populateImagesDTO();

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurchWithImage($this->churchId));

        $this
            ->uploadedFileMock
            ->method('store')
            ->willReturn($this->imagePath);

        $this
            ->imagesRepositoryMock
            ->method('create')
            ->willReturn(ChurchLists::getImageCreated($this->imageId));

        $image = $churchUploadImageService->execute($this->imagesDtoMock, $this->churchId);

        $this->assertIsObject($image);
    }

    public function test_should_return_exception_if_church_not_exists()
    {
        $churchUploadImageService = $this->getChurchUploadImageService();

        $churchUploadImageService->setPolicy(
            new Policy([
                RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_IMAGE_UPLOAD->value
            ]));

        $this->populateImagesDTO();

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $churchUploadImageService->execute($this->imagesDtoMock, $this->churchId);
    }

    public function test_should_return_exception_if_user_tries_to_upload_image_a_church_other_than_his()
    {
        $churchUploadImageService = $this->getChurchUploadImageService();

        $churchUploadImageService->setPolicy(
            new Policy([RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_IMAGE_UPLOAD->value])
        );

        $this->populateImagesDTO();

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurch());

        $this
            ->uploadedFileMock
            ->method('store')
            ->willReturn($this->imagePath);

        $this
            ->imagesRepositoryMock
            ->method('create')
            ->willReturn(ChurchLists::getImageCreated($this->imageId));


        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $churchUploadImageService->execute($this->imagesDtoMock, $this->churchId);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $churchUploadImageService = $this->getChurchUploadImageService();

        $churchUploadImageService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $churchUploadImageService->execute($this->imagesDtoMock, $this->churchId);
    }
}
