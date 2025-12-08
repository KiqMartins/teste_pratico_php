<?php

namespace App\Application\UseCase\CreateUser;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;

class CreateUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    public function execute(CreateUserInputDto $input): CreateUserOutputDto
    {
        $email = new Email($input->email);

        if ($this->repository->findByEmail($email)) {
            throw new \DomainException('User already exists');
        }

        $hashedPassword = password_hash($input->password, PASSWORD_DEFAULT);

        $user = new User($input->name, $email, $hashedPassword);

        $this->repository->save($user);

        return new CreateUserOutputDto($user->getId(), $user->getName(), (string) $user->getEmail());
    }
}