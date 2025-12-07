<?php

namespace App\Domain\Entity;

class Phone
{
    private ?int $id = null;
    private string $number;
    private ?Contact $contact = null;

    public function __construct(string $number)
    {
        $this->number = $number;
    }

    public function setContact(Contact $contact): void
    {
        $this->contact = $contact;
    }
    
    public function getNumber(): string { return $this->number; }
}