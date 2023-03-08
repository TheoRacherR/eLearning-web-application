<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Token;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class UserMailSubscriber implements EventSubscriberInterface
{

    public function __construct(private EntityManagerInterface $manager, private MailerInterface $mailer)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['sendMail', EventPriorities::POST_WRITE]
        ];
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function sendMail(ViewEvent $event)
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
        }

        $user->setToken(bin2hex(random_bytes(20)));

        $this->manager->persist($user);
        $this->manager->flush();

        $link = 'http://localhost:8080/validate/' . $user->getToken();
        $mail = (new Email())
            ->to($user->getMail())
            ->from('campagne.matthieu@gmail.com')
            ->subject('eLearning - Vérifiez votre compte')
            ->html(
                'Merci de vérifier votre compte en cliquant sur le lien suivant : <a href="' . $link . '">Vérifiez votre compte</a>'
            );

        $this->mailer->send($mail);
    }
}
