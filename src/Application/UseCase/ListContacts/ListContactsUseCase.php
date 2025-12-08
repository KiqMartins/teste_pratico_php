<?php

namespace App\Application\UseCase\ListContacts;

use App\Domain\Repository\ContactRepositoryInterface;

readonly class ListContactsUseCase
{
    public function __construct(
        private ContactRepositoryInterface $repository
    ) {}

    public function execute(int $userId, int $page = 1, int $limit = 10): array
    {
        if ($page < 1) $page = 1;
        return $this->repository->findAllByUserIdPaginated($userId, $page, $limit);
    }
}