<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class getUserByTokenController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager, private RequestStack $requestStack, private UserPasswordHasherInterface $hasher) {

    }
    public function __invoke()
    {
        $token = $_GET['token'];
        /**
         * @var User $user
         */
        $user = $this->manager->getRepository(User::class)->findOneBy(['token' => $token]);
        if($user) {
            $request = json_decode($this->requestStack->getCurrentRequest()->getContent());
            $hashedNewPassword = $this->hasher->hashPassword($user, $request->newPassword);
            $user->setPassword($hashedNewPassword);
            $this->manager->persist($user);
            $this->manager->flush();
            return new JsonResponse(['msg' => 'Votre mot de passe a bien été modifié !'], '200');
        } else {
            return new JsonResponse(['msg' => 'Le token est invalide'], '401');
        }
    }
}
