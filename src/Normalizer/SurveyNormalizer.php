<?php
declare(strict_types=1);

namespace App\Normalizer;

use App\Entity\Subscriber;
use App\Entity\Survey;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SurveyNormalizer implements NormalizerInterface, DenormalizerInterface
{
    const KEY_ID = 'id';
    const KEY_SUBSCRIBER_ID = 'subscriber_id';
    const KEY_CATEGORIES = 'categories';

    public function normalize($object, string $format = null, array $context = [])
    {
        if (!$object instanceof Survey) {
            throw new InvalidArgumentException(sprintf('Invalid class "%s"', get_class($object)));
        }

        return [
            self::KEY_ID => $object->getId(),
            self::KEY_SUBSCRIBER_ID => $object->getSubscriberId(),
            self::KEY_CATEGORIES => $object->getCategories(),
        ];
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof Subscriber;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return (new Survey())
            ->setId($data[self::KEY_ID] ?? null)
            ->setSubscriberId($data[self::KEY_SUBSCRIBER_ID] ?? null)
            ->setCategories($data[self::KEY_CATEGORIES] ?? [])
        ;
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_a($type, Subscriber::class, true);
    }
}
