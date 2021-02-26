<?php
declare(strict_types=1);

namespace App\Storage\Json;

use App\Service\FileHelper;
use App\Dto\SubscriberCollection;
use App\Entity\Subscriber;
use App\Service\IdGenerator;
use App\Storage\SubscriberStorageInterface;
use LogicException;
use Symfony\Component\Serializer\Serializer;

class SubscriberStorage implements SubscriberStorageInterface
{
    const FILE_FORMAT = 'json';

    private string $filepath;
    private FileHelper $fileHelper;
    private Serializer $serializer;
    private IdGenerator $idGenerator;

    public function __construct(
        string $filepath,
        FileHelper $fileHelper,
        Serializer $serializer,
        IdGenerator $idGenerator
    ) {
        $this->filepath = $filepath;
        $this->fileHelper = $fileHelper;
        $this->serializer = $serializer;
        $this->idGenerator = $idGenerator;
    }
    
    public function persist(Subscriber $subscriber): void
    {
        $email = $subscriber->getEmail();
        if ($email === null) {
            throw new LogicException('Email cannot be NULL');
        }

        if ($subscriber->getId() === null) {
            $subscriber->setId($this->idGenerator->generateId());
        }

        if (!file_exists($this->filepath)) {
            $subscriberCollection = new SubscriberCollection();
            $subscriberCollection->addSubscriber($subscriber);
            $this->writeFile($subscriberCollection);

            return;
        }

        $subscriberCollection = $this->getSubscriberCollection();
        $subscriberCollection->addSubscriber($subscriber);
        $this->writeFile($subscriberCollection);
    }

    private function writeFile(SubscriberCollection $subscriberCollection): void
    {
        $this->fileHelper->writeData(
            $this->serializer->serialize($subscriberCollection, self::FILE_FORMAT),
            $this->filepath
        );
    }

    public function getSubscriberCollection(): SubscriberCollection
    {
        return $this->serializer->deserialize(
            $this->fileHelper->getFileContents($this->filepath),
            SubscriberCollection::class,
            self::FILE_FORMAT
        );
    }

    public function findOneById(string $email): ?Subscriber
    {
        return $this->getSubscriberCollection()->getSubscriberById($email);
    }
}
