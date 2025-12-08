<?php

namespace App\Application\UseCase\UpdateContact;

use App\Domain\Repository\ContactRepositoryInterface;
use App\Domain\ValueObject\Email;

class UpdateContactUseCase
{
    public function __construct(
        private ContactRepositoryInterface $repository
    ) {}

    public function execute(UpdateContactInputDto $input): void
    {
        $contact = $this->repository->findByIdAndUserId($input->id, $input->userId);

        if (!$contact) {
            throw new \DomainException("Contato não encontrado ou acesso negado.");
        }

        $newEmail = new Email($input->email);
        
        if ((string) $contact->getEmail() !== (string) $newEmail) {
            
            $existing = $this->repository->findByEmail($newEmail);
            if ($existing && $existing->getId() !== $contact->getId()) {
                throw new \DomainException("Já existe outro contato com este e-mail.");
            }
            $contact->updateEmail($newEmail);
        }

        $contact->update($input->name, $input->address);

        $contact->syncPhones($input->phones);

        $this->repository->save($contact);
    }
}