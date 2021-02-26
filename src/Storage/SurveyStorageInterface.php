<?php
declare(strict_types=1);

namespace App\Storage;

use App\Dto\SurveyCollection;
use App\Entity\Survey;

interface SurveyStorageInterface
{
    public function persist(Survey $survey): void;
    public function getSurveyCollection(): SurveyCollection;
}
