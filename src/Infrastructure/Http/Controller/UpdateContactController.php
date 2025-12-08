<?php

namespace App\Infrastructure\Http\Controller;

use App\Application\UseCase\UpdateContact\UpdateContactInputDto;
use App\Application\UseCase\UpdateContact\UpdateContactUseCase;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UpdateContactController
{
    public function __construct(private UpdateContactUseCase $useCase) {}

    public function handle(ServerRequestInterface $request, array $vars): ResponseInterface
    {
        $userId = (int) $request->getHeaderLine('X-User-Id');
        if (!$userId) return new JsonResponse(['error' => 'Unauthorized'], 401);

        $contactId = (int) ($vars['id'] ?? 0);
        $data = json_decode($request->getBody()->getContents(), true);

        if (!isset($data['name'], $data['email'], $data['address'])) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }

        try {
            $input = new UpdateContactInputDto(
                $contactId,
                $userId,
                $data['name'],
                $data['email'],
                $data['address'],
                $data['phones'] ?? []
            );

            $this->useCase->execute($input);

            return new JsonResponse(['status' => 'updated'], 200);

        } catch (\DomainException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 409);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}