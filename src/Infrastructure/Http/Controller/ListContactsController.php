<?php

namespace App\Infrastructure\Http\Controller;

use App\Application\UseCase\ListContacts\ListContactsUseCase;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ListContactsController
{
    public function __construct(private ListContactsUseCase $useCase) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $userId = (int) $request->getHeaderLine('X-User-Id');
        if (!$userId) return new JsonResponse(['error' => 'Unauthorized'], 401);

        $queryParams = $request->getQueryParams();
        $page = (int) ($queryParams['page'] ?? 1);
        
        $result = $this->useCase->execute($userId, $page);

        return new JsonResponse($result);
    }
}