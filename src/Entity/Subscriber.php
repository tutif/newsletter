<?php
declare(strict_types=1);

namespace App\Entity;

class Subscriber
{
    private ?string $id;
    private ?string $email;
    private ?string $name;

    public function __construct(?string $email = null, ?string $name = null)
    {
        $this->id = null;
        $this->email = $email;
        $this->name = $name;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
