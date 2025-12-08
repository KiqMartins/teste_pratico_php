<?php

namespace App\Application\UseCase\UpdateContact;

readonly class UpdateContactInputDto
{
    public function __construct(
        public int $id,         
        public int $userId,       
        public string $name,
        public string $email,
        public string $address,
        public array $phones      
    ) {}
}