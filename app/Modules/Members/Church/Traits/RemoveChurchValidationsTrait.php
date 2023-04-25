<?php

namespace App\Modules\Members\Church\Traits;

use App\Exceptions\AppException;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use Symfony\Component\HttpFoundation\Response;

trait RemoveChurchValidationsTrait
{
    private ?object $church;

    /**
     * @throws AppException
     */
    public function validateChurchCanBeDelete(
        ChurchRepositoryInterface $churchRepository,
        string $churchId
    ): object
    {
        if(!$this->church = $churchRepository->findById($churchId, true))
        {
            throw new AppException(
                MessagesEnum::REGISTER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        $this->churchHasMembers();
        $this->churchHasResponsible();

        return $this->church;
    }

    /**
     * @throws AppException
     */
    private function churchHasMembers(): void
    {
        if(count($this->church->user) > 0)
        {
            throw new AppException(
                MessagesEnum::CHURCH_HAS_MEMBERS_IN_DELETE,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    private function churchHasResponsible(): void
    {
        if(count($this->church->adminUser) > 0)
        {
            throw new AppException(
                MessagesEnum::CHURCH_HAS_RESPONSIBLE_IN_DELETE,
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
