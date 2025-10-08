<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Form\ContactMessageType;
use App\Repository\ContactMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/contact')]
class ContactController extends AbstractController
{
    #[Route('/', name: 'app_contact')]
    public function index(
        Request $request, 
        MailerInterface $mailer,
        ContactMessageRepository $repo,
        EntityManagerInterface $em
    ): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour envoyer un message.');
            return $this->redirectToRoute('app_login');
        }

        $lastMessage = $repo->findLastMessageByUser($user);
        if ($lastMessage && $lastMessage->getCreatedAt() > new \DateTime('-1 hour')) {
            $this->addFlash('error', 'Vous ne pouvez envoyer qu’un message toutes les heures.');
            return $this->redirectToRoute('app_home');
        }

        $message = new ContactMessage();
        $message->setUser($user);

        $form = $this->createForm(ContactMessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $attachmentFile = $form->get('attachment')->getData();
            if ($attachmentFile) {
                $newFilename = uniqid().'-'.$attachmentFile->getClientOriginalName();
                try {
                    $attachmentFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                    $message->setAttachmentFilename($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload du fichier.');
                    return $this->redirectToRoute('app_contact');
                }
            }

            $em->persist($message);
            $em->flush();

            $email = (new Email())
                ->from($user->getEmail())
                ->to('contact@example.com') // email d'un admin par exemple
                ->subject($message->getSubject())
                ->text($message->getMessage());

            if ($message->getAttachmentFilename()) {
                $email->attachFromPath($this->getParameter('uploads_directory').'/'.$message->getAttachmentFilename());
            }

            $mailer->send($email);

            $this->addFlash('success', 'Message envoyé avec succès !');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
