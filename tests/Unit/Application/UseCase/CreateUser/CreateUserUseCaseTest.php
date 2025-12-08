<?php

namespace Tests\Unit\Application\UseCase\CreateUser;

use App\Application\UseCase\CreateUser\CreateUserUseCase;
use App\Application\UseCase\CreateUser\CreateUserInputDto;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

class CreateUserUseCaseTest extends TestCase
{
    public function testCanCreateUserUseCase(): void
    {
        $repository = $this->createMock(UserRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('findByEmail')
            ->willReturn(null);
        
        $repository->expects($this->once())
            ->method('save'); 

        $useCase = new CreateUserUseCase($repository);
        $input = new CreateUserInputDto('Vendedor 1', 'teste@teste.com', '123456');
        
        $output = $useCase->execute($input);

        $this->assertEquals('Vendedor 1', $output->name);
    }

    public function testCannotCreateDuplicateUser(): void
    {
        $repository = $this->createMock(UserRepositoryInterface::class);
        $repository->method('findByEmail')->willReturn($this->createMock(User::class));
        
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('User already exists');

        $useCase = new CreateUserUseCase($repository);
        $useCase->execute(new CreateUserInputDto('Vendedor', 'teste@teste.com', '123'));
    }
}