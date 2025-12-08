<?php

namespace Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function testCanCreateValidEmail(): void
    {
        $email = new Email('dev@test.com.br');
        $this->assertEquals('dev@test.com.br', (string) $email);
    }

    public function testThrowsExceptionForInvalidEmail(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('invalid e-mail');
        
        new Email('invalid e-mail');
    }
}