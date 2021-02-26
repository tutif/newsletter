<?php
declare(strict_types=1);

namespace App\Normalizer;

use App\Dto\SubscriberCollection;
use App\Entity\Subscriber;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SubscriberCollectionNormalizer implements NormalizerInterface, DenormalizerInterface
{
    const KEY_SUBSCRIBERS = 'subscribers';

    private SubscriberNormalizer $subscriberNormalizer;

    public function __construct(SubscriberNormalizer $subscriberNormalizer)
    {
        $this->subscriberNormalizer = $subscriberNormalizer;
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        if (!$object instanceof SubscriberCollection) {
            throw new InvalidArgumentException(sprintf('Invalid class "%s"', get_class($object)));
        }

        $subscribersNormalized = [];
        foreach ($object->getSubscribers() as $subscriber) {
            $subscribersNormalized[] = $this->subscriberNormalizer->normalize($subscriber);
        }

        return [self::KEY_SUBSCRIBERS => $subscribersNormalized];
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof SubscriberCollection;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $subscriberCollection = new SubscriberCollection();
        foreach ($data[self::KEY_SUBSCRIBERS] as $subscriberData) {
            $subscriberCollection->addSubscriber(
                $this->subscriberNormalizer->denormalize($subscriberData, Subscriber::class)
            );
        }

        return $subscriberCollection;
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_a($type, SubscriberCollection::class, true);
    }
}
