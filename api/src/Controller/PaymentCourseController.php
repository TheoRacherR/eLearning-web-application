<?php

namespace App\Controller;

use App\ApiResources\CoursesList;
use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class PaymentCourseController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager) {

    }
    /**
     * @throws ApiErrorException
     */
    public function __invoke(CoursesList $coursesList, RequestStack $requestStack)
    {
        $stripe = new \Stripe\StripeClient(
            'sk_test_51MRvy2I8On5YmTNNSW1T6vjY1Qf27P8l62eZgOEECJYbSKaRXlP6HoBHnHmHB7adcGZHNfbuCnTjROFEQOXjummg00kZ0bQcBv'
        );
        $response = $requestStack->getCurrentRequest()->getContent();
        $products_tab = json_decode($response);
        $tab = [];
        foreach($products_tab->products as $product) {
            $tab[] = ['price' => $product->priceId, 'quantity' => 1];
        }
        $link = $stripe->checkout->sessions->create([
            'success_url' => 'http://learn.matthieucmp.eu/summary',
            'line_items' => [
                $tab
            ],
            'mode' => 'payment',
        ]);

        return new JsonResponse($link, '200');
    }
}
