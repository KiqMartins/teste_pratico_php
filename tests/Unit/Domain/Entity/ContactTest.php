<?php

namespace Tests\Unit\Domain\Entity;

use App\Domain\Entity\Contact;
use App\Domain\Entity\Phone;
use App\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    public function testCanCreateContact(): void
    {
        $email = new Email('teste@example.com');
        $contact = new Contact('João Silva', $email, 'Rua PHP, 82');

        $this->assertEquals('João Silva', $contact->getName());
        $this->assertEquals('teste@example.com', (string) $contact->getEmail());
        $this->assertCount(0, $contact->getPhones());
    }

    public function testCanAddPhones(): void
    {
        $contact = new Contact('Maria', new Email('maria@teste.com'), 'Rua X');
        
        $phone1 = new Phone('(11) 99999-0000');
        $phone2 = new Phone('(21) 88888-1111');

        $contact->addPhone($phone1);
        $contact->addPhone($phone2);
        
        $contact->addPhone($phone1); 

        $this->assertCount(2, $contact->getPhones());
    }

    public function testCanUpdateContactData(): void
    {
        $contact = new Contact('Original', new Email('orig@teste.com'), 'Endereço Velho');
        
        $contact->update('Novo Nome', 'Endereço Novo');

        $this->assertEquals('Novo Nome', $contact->getName());
        $this->assertEquals('Endereço Novo', $contact->getAddress());
    }
}