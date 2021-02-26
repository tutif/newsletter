<?php
declare(strict_types=1);

namespace App\Dto;

use App\Entity\Survey;

class SurveyCollection
{
    /**
     * @var Survey[]
     */
    private array $surveys;

    public function __construct()
    {
        $this->surveys = [];
    }

    public function addSurvey(Survey $survey): self
    {
        $id = $survey->getId();
        if ($id === null) {
            throw new \LogicException('Id is required');
        }
        $this->surveys[$id] = $survey;

        return $this;
    }

    /**
     * @return Survey[] ['id' => Survey, 'id2' => Survey, ...]
     */
    public function getSurveys(): array
    {
        return $this->surveys;
    }

    public function getSurveyById(string $id): ?Survey
    {
        return $this->surveys[$id] ?? null;
    }
}
