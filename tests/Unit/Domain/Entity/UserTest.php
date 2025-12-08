<?php

namespace Tests\Unit\Domain\Entity;

use App\Domain\Entity\User;
use App\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testCanCreateUser(): void
    {
        $email = new Email('vendedor@empresa.com');
        
        $user = new User('Carlos Vendedor', $email, 'hashed_password_123');

        $this->assertEquals('Carlos Vendedor', $user->getName());
        $this->assertEquals('vendedor@empresa.com', (string) $user->getEmail());
        $this->assertEquals('hashed_password_123', $user->getPassword());
    }

    public function testUserInitializeWithEmptyContacts(): void
    {
        $user = new User('Carlos', new Email('c@c.com'), 'pass');
        $this->assertCount(0, $user->getContacts());
    }
}