<?php

namespace App\Infrastructure\Http\Controller;

use App\Application\UseCase\CreateContact\CreateContactInputDto;
use App\Application\UseCase\CreateContact\CreateContactUseCase;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateContactController
{
    public function __construct(
        private CreateContactUseCase $useCase
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $userIdHeader = $request->getHeaderLine('X-User-Id');
        
        if (empty($userIdHeader)) {
            return new JsonResponse(['error' => 'Unauthorized: X-User-Id header missing'], 401);
        }

        $userId = (int) $userIdHeader;
        $data = json_decode($request->getBody()->getContents(), true);

        if (!isset($data['name'], $data['email'], $data['address'])) {
            return new JsonResponse(['error' => 'Missing required fields (name, email, address)'], 400);
        }

        try {
            $input = new CreateContactInputDto(
                $userId,
                $data['name'],
                $data['email'],
                $data['address'],
                $data['phones'] ?? [] 
            );

            $output = $this->useCase->execute($input);

            return new JsonResponse(['data' => (array) $output], 201);

        } catch (\DomainException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 409); 
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal Server Error'], 500);
        }
    }
}