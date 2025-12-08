<?php

namespace App\Application\UseCase\CreateContact;

use App\Domain\Entity\Contact;
use App\Domain\Entity\Phone;
use App\Domain\Repository\ContactRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface; 
use App\Domain\ValueObject\Email;

class CreateContactUseCase
{
    public function __construct(
        private ContactRepositoryInterface $contactRepository,
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(CreateContactInputDto $input): CreateContactOutputDto
    {
        $user = $this->userRepository->findById($input->userId);
        
        if (!$user) {
            throw new \DomainException("Usuário (Vendedor) não encontrado.");
        }

        $email = new Email($input->email);

        if ($this->contactRepository->findByEmail($email)) {
            throw new \DomainException("Este contato já existe na base de dados.");
        }

        $contact = new Contact($input->name, $email, $input->address);
        
        $contact->setUser($user);

        foreach ($input->phones as $phoneNumber) {
            if (!empty($phoneNumber)) {
                $contact->addPhone(new Phone($phoneNumber));
            }
        }

        $this->contactRepository->save($contact);

        return new CreateContactOutputDto(
            $contact->getId(), 
            $contact->getName(), 
            (string) $contact->getEmail(),
            $contact->getPhones()->count()
        );
    }
}