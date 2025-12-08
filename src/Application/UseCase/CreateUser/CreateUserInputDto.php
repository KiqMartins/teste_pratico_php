<?php
namespace App\Application\UseCase\CreateUser;

readonly class CreateUserInputDto {
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    ) {}
}