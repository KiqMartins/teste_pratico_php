<?php

namespace App\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Domain\ValueObject\Email;

class User
{
    private ?int $id = null;
    private string $name;
    private Email $email;
    private string $password;
    private Collection $contacts;

    public function __construct(string $name, Email $email, string $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->contacts = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): Email { return $this->email; }
    public function getPassword(): string { return $this->password; }
    public function getContacts(): Collection { return $this->contacts; }
    
    public function changePassword(string $newHashedPassword): void
    {
        $this->password = $newHashedPassword;
    }
}