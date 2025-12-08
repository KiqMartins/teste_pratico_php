<?php

namespace App\Application\UseCase\CreateContact;

readonly class CreateContactOutputDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public int $phoneCount
    ) {}
}