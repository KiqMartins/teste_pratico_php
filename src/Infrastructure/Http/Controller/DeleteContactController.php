<?php

namespace App\Infrastructure\Http\Controller;

use App\Application\UseCase\DeleteContact\DeleteContactUseCase;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DeleteContactController
{
    public function __construct(private DeleteContactUseCase $useCase) {}

    public function handle(ServerRequestInterface $request, array $vars): ResponseInterface
    {
        $userId = (int) $request->getHeaderLine('X-User-Id');
        if (!$userId) return new JsonResponse(['error' => 'Unauthorized'], 401);

        $contactId = (int) ($vars['id'] ?? 0);

        try {
            $this->useCase->execute($contactId, $userId);
            return new JsonResponse(null, 204);
            
        } catch (\DomainException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        }
    }
}