<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Course;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $users = $manager->getRepository(User::class)->findAll();
        $courses = $manager->getRepository(Course::class)->findAll();
        $faker = Factory::create('fr_FR');
        for($i = 0; $i < 20; $i++) {
            $comment = new Comment();
            $comment->setUserId($faker->randomElement($users))
                ->setCourse($faker->randomElement($courses))
                ->setContent('commentaire '.$i)
                ->setStar($faker->randomNumber(1))
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setValid(true);

            $manager->persist($comment);
            $manager->flush();
        }
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class, CourseFixtures::class];
    }
}
