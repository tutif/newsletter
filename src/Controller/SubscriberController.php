<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Subscriber;
use App\Form\SubscriberFormType;
use App\SessionKeys;
use App\Storage\SubscriberStorageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class SubscriberController extends AbstractController
{
    private SubscriberStorageInterface $subscriberStorage;

    public function __construct(SubscriberStorageInterface $subscriberStorage)
    {
        $this->subscriberStorage = $subscriberStorage;
    }

    /**
     * @Route("/subscriber", name="subscriber")
     *
     * @param Request $request
     * @param SessionInterface $session
     *
     * @return Response
     */
    public function index(Request $request, SessionInterface $session): Response
    {
        $subscriber = new Subscriber();

        $form = $this->createForm(SubscriberFormType::class, $subscriber);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->subscriberStorage->persist($subscriber);
            $session->set(SessionKeys::SUBSCRIBER_ID, $subscriber->getId());

            return $this->redirectToRoute('survey');
        }

        return $this->render('subscriber/index.html.twig', ['form' => $form->createView()]);
    }
}
