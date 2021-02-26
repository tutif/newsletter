<?php
declare(strict_types=1);

namespace App\Storage\Json;

use App\Dto\SurveyCollection;
use App\Entity\Survey;
use App\Service\FileHelper;
use App\Service\IdGenerator;
use App\Storage\SurveyStorageInterface;
use Symfony\Component\Serializer\Serializer;

class SurveyStorage implements SurveyStorageInterface
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
    
    public function persist(Survey $survey): void
    {
        if ($survey->getId() === null) {
            $survey->setId($this->idGenerator->generateId());
        }

        if (!file_exists($this->filepath)) {
            $surveyCollection = new SurveyCollection();
            $surveyCollection->addSurvey($survey);
            $this->writeFile($surveyCollection);

            return;
        }

        $surveyCollection = $this->getSurveyCollection();
        $surveyCollection->addSurvey($survey);

        $this->writeFile($surveyCollection);
    }

    private function writeFile(SurveyCollection $surveyCollection): void
    {
        $this->fileHelper->writeData(
            $this->serializer->serialize($surveyCollection, self::FILE_FORMAT),
            $this->filepath
        );
    }

    public function getSurveyCollection(): SurveyCollection
    {
        return $this->serializer->deserialize(
            $this->fileHelper->getFileContents($this->filepath),
            SurveyCollection::class,
            self::FILE_FORMAT
        );
    }
}
