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
            throw new AccessDeniedHttpException('Veuillez confirmer votre compte en cliquant sur le lien dans le mail', null, '403');
        }
        if(true === $user->isBan()) {
            throw new AccessDeniedHttpException('Your account is banned');
        }
        // Add information to user payload
        $payload += [
            'user_id' => $user->getId(),
            'mail' => $user->getMail(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'valid' => $user->isValid(),
            'ban' => $user->isBan(),
            'roles' => $user->getRoles()
        ];
        $event->setData($payload);
    }
}
