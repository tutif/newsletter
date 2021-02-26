<?php
declare(strict_types=1);

namespace App\Normalizer;

use App\Dto\SurveyCollection;
use App\Entity\Survey;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SurveyCollectionNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private SurveyNormalizer $surveyNormalizer;

    public function __construct(SurveyNormalizer $surveyNormalizer)
    {
        $this->surveyNormalizer = $surveyNormalizer;
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        if (!$object instanceof SurveyCollection) {
            throw new InvalidArgumentException(sprintf('Invalid class "%s"', get_class($object)));
        }

        $surveysNormalized = [];
        foreach ($object->getSurveys() as $survey) {
            $surveysNormalized[] = $this->surveyNormalizer->normalize($survey);
        }

        return ['surveys' => $surveysNormalized];
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof SurveyCollection;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $surveyCollection = new SurveyCollection();
        foreach ($data['surveys'] as $surveyData) {
            $surveyCollection->addSurvey(
                $this->surveyNormalizer->denormalize($surveyData, Survey::class)
            );
        }

        return $surveyCollection;
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_a($type, SurveyCollection::class, true);
    }
}
