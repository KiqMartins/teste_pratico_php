<?php

namespace App\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Domain\ValueObject\Email;

class Contact
{
    private ?int $id = null;
    private string $name;
    private Email $email;
    private string $address;
    private Collection $phones;
    private ?User $user = null; 

    public function __construct(string $name, Email $email, string $address)
    {
        $this->name = $name;
        $this->email = $email;
        $this->address = $address;
        $this->phones = new ArrayCollection();
    }

    public function addPhone(Phone $phone): void
    {
        if (!$this->phones->contains($phone)) {
            $this->phones->add($phone);
            $phone->setContact($this);
        }
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
    public function update(string $name, string $address): void
    {
        $this->name = $name;
        $this->address = $address;
    }

    public function getAddress(): string { return $this->address; }
    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): Email { return $this->email; }
    public function getPhones(): Collection { return $this->phones; }
    
}