<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Survey;
use App\Form\SurveyType;
use App\Service\IdGenerator;
use App\SessionKeys;
use App\Storage\SubscriberStorageInterface;
use App\Storage\SurveyStorageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class SurveyController extends AbstractController
{
    private SubscriberStorageInterface $subscriberStorage;
    private SurveyStorageInterface $surveyStorage;

    public function __construct(
        SubscriberStorageInterface $subscriberStorage,
        SurveyStorageInterface $surveyStorage
    ) {
        $this->subscriberStorage = $subscriberStorage;
        $this->surveyStorage = $surveyStorage;
    }
    
    /**
     * @Route("/survey", name="survey")
     *
     * @param Session $session
     *
     * @return Response
     */
    public function index(Request $request, Session $session): Response
    {
        if (!$session->has(SessionKeys::SUBSCRIBER_ID)) {
            $this->addFlash('notice', 'Please sign up first before configuring your newsletter subscription.');

            return $this->redirectToRoute('subscriber');
        }

        $subscriberId = $session->get(SessionKeys::SUBSCRIBER_ID);
        $subscriber = $this->subscriberStorage->findOneById($subscriberId);
        if ($subscriber === null) {
            $this->addFlash('notice', 'Data for the subscriber no longer exists, please sign up again.');

            return $this->redirectToRoute('subscriber');
        }

        $survey = new Survey();
        $survey->setSubscriberId($subscriberId);

        $form = $this->createForm(SurveyType::class, $survey);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->surveyStorage->persist($survey);

            return $this->render('survey/success.html.twig');
        }

        return $this->render('survey/index.html.twig', [
            'subscriber' => $subscriber,
            'form' => $form->createView(),
        ]);
    }
}
