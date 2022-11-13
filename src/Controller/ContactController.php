<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message();
        $form = $this->createForm(ContactType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $message->setEmail($form->get('email')->getData());
            $message->setContent($form->get('content')->getData());

            $entityManager->persist($message);
            $entityManager->flush();

            return $this->render('contact/success.html.twig', [
                'message' => $message
            ]);

        }else{
            return $this->renderForm('contact/index.html.twig', [
                'ContactForm' => $form
            ]);
        }
    }
}
