<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\Survey;
use App\Storage\Json\SubscriberStorage;
use App\Entity\Subscriber;
use App\Storage\SubscriberStorageInterface;
use App\Storage\SurveyStorageInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\VarDumper\VarDumper;

class TestCommand extends Command
{
    protected static $defaultName = 'app:test';

    private SubscriberStorageInterface $subscriberStorage;
    private SurveyStorageInterface $surveyStorage;

    public function __construct(
        SubscriberStorageInterface $subscriberStorage,
        SurveyStorageInterface $surveyStorage
    ) {
        parent::__construct();

        $this->subscriberStorage = $subscriberStorage;
        $this->surveyStorage = $surveyStorage;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $subscriber = new Subscriber('peter@example.com', 'Peter');
        $this->subscriberStorage->persist($subscriber);

        $this->subscriberStorage->persist(new Subscriber('arnoldas@example.com', 'Arnoldas'));
        $this->subscriberStorage->persist(
            (new Subscriber('arnoldas@example.com', 'Peter S.'))
                ->setId($subscriber->getId())
        );

        $survey = (new Survey())
            ->setCategories(['foo', 'bar'])
            ->setSubscriberId($subscriber->getId())
        ;
        $this->surveyStorage->persist($survey);

        $symfonyStyle = new SymfonyStyle($input, $output);

        $symfonyStyle->section('Subscribers');
        VarDumper::dump($this->subscriberStorage->getSubscriberCollection());

        $symfonyStyle->section('Surveys');
        foreach ($this->surveyStorage->getSurveyCollection()->getSurveys() as $survey) {
            VarDumper::dump($survey);
        }

        return Command::SUCCESS;
    }
}
