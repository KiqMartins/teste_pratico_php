<?php

namespace App\Infrastructure\Http\Controller;

use App\Domain\Repository\ContactRepositoryInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetContactController
{
    public function __construct(
        private ContactRepositoryInterface $repository
    ) {}

    public function handle(ServerRequestInterface $request, array $vars): ResponseInterface
    {
        $userId = (int) $request->getHeaderLine('X-User-Id');
        if (!$userId) return new JsonResponse(['error' => 'Unauthorized'], 401);

        $contactId = (int) ($vars['id'] ?? 0);
        $contact = $this->repository->findByIdAndUserId($contactId, $userId);

        if (!$contact) {
            return new JsonResponse(['error' => 'Contact not found'], 404);
        }

        $payload = [
            'id' => $contact->getId(),
            'name' => $contact->getName(),
            'email' => (string) $contact->getEmail(),
            'address' => $contact->getAddress(),
            'phones' => $contact->getPhones()->map(fn($p) => $p->getNumber())->toArray()
        ];

        return new JsonResponse(['data' => $payload]);
    }
}