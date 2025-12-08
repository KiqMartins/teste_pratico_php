<?php
namespace App\Application\UseCase\CreateUser;

readonly class CreateUserOutputDto {
    public function __construct(
        public ?int $id,
        public string $name,
        public string $email
    ) {}
}