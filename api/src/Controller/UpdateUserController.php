<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateUserController extends AbstractController
{
    public function __construct(private RequestStack $requestStack, private UserPasswordHasherInterface $passwordHasher, private EntityManagerInterface $manager)
    {
    }

    public function __invoke(User $user)
    {
        $request = $this->requestStack->getCurrentRequest()->getContent();
        $datas = json_decode($request);
        if (strlen($datas->firstname) > 0) {
            $user->setFirstname($datas->firstname);
        }
        if (strlen($datas->lastname) > 0) {
            $user->setLastname($datas->lastname);
        }
        if (strlen($datas->password) > 0) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $datas->password));
        }

        $this->manager->persist($user);
        $this->manager->flush();

        return new JsonResponse('Mise à jour du profil effectuée', '200');
    }
}
