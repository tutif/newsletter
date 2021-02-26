<?php
declare(strict_types=1);

namespace App\Normalizer;

use App\Entity\Subscriber;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SubscriberNormalizer implements NormalizerInterface, DenormalizerInterface
{
    const KEY_ID = 'id';
    const KEY_EMAIL = 'email';
    const KEY_NAME = 'name';

    public function normalize($object, string $format = null, array $context = [])
    {
        if (!$object instanceof Subscriber) {
            throw new InvalidArgumentException(sprintf('Invalid class "%s"', get_class($object)));
        }

        return [
            self::KEY_ID => $object->getId(),
            self::KEY_EMAIL => $object->getEmail(),
            self::KEY_NAME => $object->getName(),
        ];
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof Subscriber;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if (!isset($data[self::KEY_EMAIL])) {
            throw new InvalidArgumentException('Missing key "email"');
        }

        return (new Subscriber())
            ->setId($data[self::KEY_ID] ?? null)
            ->setEmail($data[self::KEY_EMAIL] ?? null)
            ->setName($data[self::KEY_NAME] ?? null)
        ;
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_a($type, Subscriber::class, true);
    }
}
