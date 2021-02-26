<?php
declare(strict_types=1);

namespace App\Entity;

class Survey
{
    private ?string $id;
    private ?string $subscriberId;

    /**
     * @var string[]
     */
    private array $categories;

    public function __construct()
    {
        $this->id = null;
        $this->subscriberId = null;
        $this->categories = [];
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setSubscriberId(?string $subscriberId): self
    {
        $this->subscriberId = $subscriberId;

        return $this;
    }

    public function getSubscriberId(): ?string
    {
        return $this->subscriberId;
    }

    /**
     * @param string[] $categories
     *
     * @return $this
     */
    public function setCategories(array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }
}
