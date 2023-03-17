<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Provider\Lorem;

class CourseFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $users = $manager->getRepository(User::class)->findAll();
        $faker = Factory::create('fr_FR');
        for($i = 0; $i < 20; $i++) {
            $course = new Course();
            $course->setUserId($faker->randomElement($users))
                ->setTitle($faker->name)
                ->setDescription('Description')
                ->setContent('Content')
                ->setPrice($faker->randomNumber(2))
                ->setStripePriceId($faker->slug)
                ->setStripeProductId($faker->slug)
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setValid(true);

            $manager->persist($course);
            $manager->flush();
        }
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
