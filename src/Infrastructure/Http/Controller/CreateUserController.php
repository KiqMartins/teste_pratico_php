<?php

namespace App\Infrastructure\Http\Controller;

use App\Application\UseCase\CreateUser\CreateUserInputDto;
use App\Application\UseCase\CreateUser\CreateUserUseCase;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateUserController
{
    public function __construct(
        private CreateUserUseCase $useCase
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true);

        if (!isset($data['name'], $data['email'], $data['password'])) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }

        try {
            $input = new CreateUserInputDto($data['name'], $data['email'], $data['password']);
            $output = $this->useCase->execute($input);

            return new JsonResponse([
                'data' => [
                    'id' => $output->id,
                    'name' => $output->name,
                    'email' => $output->email
                ]
            ], 201);

        } catch (\DomainException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 409); 
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400); 
        }
    }
}