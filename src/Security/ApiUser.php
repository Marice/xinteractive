<?php
namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class ApiUser implements UserInterface
{
    private string $username;

    public function __construct(
        string $username
    )
    {
        $this->username = $username;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getRoles(): array
    {
        return ['ROLE_API'];
    }

    public function eraseCredentials(): void {}

    public function getPassword(): ?string
    {
        return null;
    }
}