<?php

namespace App\Controller;

use App\Entity\Course;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Stripe\Exception\ApiErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CreateCourseController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager)
    {
    }

    /**
     * @throws ApiErrorException
     */
    public function __invoke(Course $course) {
        $stripe = new \Stripe\StripeClient(
            'sk_test_51MRvy2I8On5YmTNNSW1T6vjY1Qf27P8l62eZgOEECJYbSKaRXlP6HoBHnHmHB7adcGZHNfbuCnTjROFEQOXjummg00kZ0bQcBv'
        );

        $product = $stripe->products->create([
            'name' => $course->getTitle(),
        ]);

        $price = $stripe->prices->create([
          "active" => true,
          "currency" => "eur",
          "product" => $product->id,
          "unit_amount" => $course->getPrice() * 100,
        ]);

        $course->setStripeProductId($product->id);
        $course->setStripePriceId($price->id);

        $this->manager->persist($course);
        $this->manager->flush($course);

        return new JsonResponse('Course has been created', '200');
    }
}
