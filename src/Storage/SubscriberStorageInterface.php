<?php
declare(strict_types=1);

namespace App\Storage;

use App\Dto\SubscriberCollection;
use App\Entity\Subscriber;

interface SubscriberStorageInterface
{
    public function persist(Subscriber $subscriber): void;
    public function getSubscriberCollection(): SubscriberCollection;
    public function findOneById(string $email): ?Subscriber;
}
