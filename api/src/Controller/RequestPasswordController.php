<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class RequestPasswordController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager, private MailerInterface $mailer)
    {
    }

    public function __invoke()
    {
        $mail = $_GET['mail'];
        try {
            if (false === $mail) {
                throw new \Exception('Le mail est manquant');
            }
            /**
             * @var User $user
             */
            $user = $this->manager->getRepository(User::class)->findOneBy(['mail' => $mail]);
            if (!$user) {
                throw new \Exception('Le mail est invalide');
            }
            $user->setToken(bin2hex(random_bytes(20)));
            $this->manager->persist($user);
            $this->manager->flush();

            $link = 'http://localhost:8080/reset_password?token=' . $user->getToken();
            $mail = (new Email())
                ->from('campagne.matthieu@gmail.com')
                ->to($user->getMail())
                ->subject('eLearning - Votre demande de changement de mot de passe')
                ->html(
                    'Cliquez sur le lien suivant pour réinitialiser votre mot de passe : <a href="' . $link . '">Réinitialisez votre mot de passe</a>'
                );
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), '404');
        }
        return new JsonResponse('Un email vous a été envoyé, avec un lien pour réinitialiser votre mot de passe', '200');
    }
}
