<?php

namespace Tests\Unit\Application\UseCase\CreateContact;

use App\Application\UseCase\CreateContact\CreateContactInputDto;
use App\Application\UseCase\CreateContact\CreateContactUseCase;
use App\Domain\Entity\Contact;
use App\Domain\Entity\User;
use App\Domain\Repository\ContactRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

class CreateContactUseCaseTest extends TestCase
{
    public function testCanCreateContactSuccessfully(): void
    {
        $userRepo = $this->createMock(UserRepositoryInterface::class);
        $userMock = $this->createMock(User::class); 
        $userRepo->method('findById')->willReturn($userMock);

        $contactRepo = $this->createMock(ContactRepositoryInterface::class);
        $contactRepo->method('findByEmail')->willReturn(null); 
        
        $contactRepo->expects($this->once())
            ->method('save')
            ->will($this->returnCallback(function (Contact $contact) {
                
                $reflection = new \ReflectionClass($contact);
                $property = $reflection->getProperty('id');
                $property->setAccessible(true);
                $property->setValue($contact, 99);
            }));


        $useCase = new CreateContactUseCase($contactRepo, $userRepo);
        
        $input = new CreateContactInputDto(
            userId: 1,
            name: 'Cliente Teste',
            email: 'cliente@teste.com',
            address: 'Rua das Flores',
            phones: ['1199999999', '1188888888'] 
        );

        $output = $useCase->execute($input);

        $this->assertEquals(99, $output->id);
        $this->assertEquals('Cliente Teste', $output->name);
        $this->assertEquals(2, $output->phoneCount); 
    }

    public function testThrowsExceptionIfUserNotFound(): void
    {
        $userRepo = $this->createMock(UserRepositoryInterface::class);
        $userRepo->method('findById')->willReturn(null); 

        $contactRepo = $this->createMock(ContactRepositoryInterface::class);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário (Vendedor) não encontrado');

        $useCase = new CreateContactUseCase($contactRepo, $userRepo);
        $useCase->execute(new CreateContactInputDto(1, 'Nome', 'e@e.com', 'End', []));
    }

    public function testThrowsExceptionIfEmailAlreadyExists(): void
    {
        $userRepo = $this->createMock(UserRepositoryInterface::class);
        $userRepo->method('findById')->willReturn($this->createMock(User::class));

        $contactRepo = $this->createMock(ContactRepositoryInterface::class);
        $contactRepo->method('findByEmail')->willReturn($this->createMock(Contact::class));

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Este contato já existe na base de dados');

        $useCase = new CreateContactUseCase($contactRepo, $userRepo);
        $useCase->execute(new CreateContactInputDto(1, 'Nome', 'duplicado@e.com', 'End', []));
    }
}