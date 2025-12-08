<?php

namespace App\Application\UseCase\CreateContact;

readonly class CreateContactInputDto
{
    public function __construct(
        public int $userId,
        public string $name,
        public string $email,
        public string $address,
        public array $phones = []
    ) {}
}