<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Contact;
use App\Domain\ValueObject\Email;

interface ContactRepositoryInterface
{
    public function save(Contact $contact): void;
    public function findByEmail(Email $email): ?Contact;
    public function delete(Contact $contact): void;
    public function findByIdAndUserId(int $id, int $userId): ?Contact;
    public function findAllByUserIdPaginated(int $userId, int $page, int $limit): array;
}