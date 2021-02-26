<?php
declare(strict_types=1);

namespace App\Dto;

use App\Entity\Subscriber;

class SubscriberCollection
{
    /**
     * @var Subscriber[]
     */
    private array $subscribers;

    public function __construct()
    {
        $this->subscribers = [];
    }

    public function addSubscriber(Subscriber $subscriber): self
    {
        $id = $subscriber->getId();
        if ($id === null) {
            throw new \LogicException('Id cannot be NULL');
        }
        $this->subscribers[$id] = $subscriber;

        return $this;
    }

    /**
     * @return Subscriber[] ['id' => Subscriber, 'id2' => Subscriber, ...]
     */
    public function getSubscribers(): array
    {
        return $this->subscribers;
    }

    public function getSubscriberById(string $id): ?Subscriber
    {
        return $this->subscribers[$id] ?? null;
    }

    // todo: create findSubscriberByEmail, use for duplicates
}
