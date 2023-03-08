<?php

namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class LoginSuccessListener
{

    public function onLoginSuccess(AuthenticationSuccessEvent $event): void
    {
        $user = $event->getUser();
        $payload = $event->getData();
        if (!$user instanceof User) {
            return;
        }

        if (false === $user->isValid()) {
            throw new AccessDeniedHttpException('Please confirm your account with the email we sent you', null, '403');
        }
        // Add information to user payload
        $payload += [
            'user_id' => $user->getId(),
            'mail' => $user->getMail(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'valid' => $user->isValid(),
            'roles' => $user->getRoles()
        ];
        $event->setData($payload);
    }
}
