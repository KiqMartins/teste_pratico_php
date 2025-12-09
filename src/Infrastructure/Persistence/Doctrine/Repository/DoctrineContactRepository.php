<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Entity\Contact;
use App\Domain\Repository\ContactRepositoryInterface;
use App\Domain\ValueObject\Email;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ArrayParameterType;

class DoctrineContactRepository implements ContactRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Connection $connection
    ) {}

    public function save(Contact $contact): void
    {
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
    }

    public function findByEmail(Email $email): ?Contact
    {
        return $this->entityManager->getRepository(Contact::class)->findOneBy([
            'email.value' => (string) $email
        ]);
    }

    public function delete(Contact $contact): void
    {
        $this->entityManager->remove($contact);
        $this->entityManager->flush();
    }

    public function findByIdAndUserId(int $id, int $userId): ?Contact
    {
        return $this->entityManager->getRepository(Contact::class)->findOneBy([
            'id' => $id,
            'user' => $userId 
        ]);
    }

   public function findAllByUserIdPaginated(int $userId, int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        $qb = $this->connection->createQueryBuilder();
        
        $query = $qb->select('c.id', 'c.name', 'c.email', 'c.address') 
            ->from('contacts', 'c')
            ->where('c.user_id = :userId')
            ->orderBy('c.name', 'ASC')
            ->setParameter('userId', $userId);

        $countQuery = clone $query;
        $total = (int) $countQuery->select('COUNT(c.id)')->executeQuery()->fetchOne();

        $contacts = $query->select('c.id', 'c.name', 'c.email', 'c.address')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->executeQuery()
            ->fetchAllAssociative();
        
        if (!empty($contacts)) {
            $contactIds = array_column($contacts, 'id');

            $qbPhone = $this->connection->createQueryBuilder();
            $phones = $qbPhone->select('p.contact_id', 'p.number')
                ->from('phones', 'p')
                ->where('p.contact_id IN (:ids)')
                ->setParameter('ids', $contactIds, ArrayParameterType::INTEGER)
                ->executeQuery()
                ->fetchAllAssociative();

            $phonesByContact = [];
            foreach ($phones as $phone) {
                $phonesByContact[$phone['contact_id']][] = ['number' => $phone['number']];
            }

            foreach ($contacts as &$contact) {
                $contact['phones'] = $phonesByContact[$contact['id']] ?? [];
            }
        }

        return [
            'data' => $contacts,
            'total' => $total,
            'page' => $page,
            'limit' => $limit
        ];
    }
}