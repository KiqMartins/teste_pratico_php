<?php

namespace App\Application\UseCase\DeleteContact;

use App\Domain\Repository\ContactRepositoryInterface;

readonly class DeleteContactUseCase
{
    public function __construct(
        private ContactRepositoryInterface $repository
    ) {}

    public function execute(int $contactId, int $userId): void
    {
        $contact = $this->repository->findByIdAndUserId($contactId, $userId);

        if (!$contact) {
            throw new \DomainException("Contato não encontrado ou acesso negado.");
        }

        $this->repository->delete($contact);
    }
}