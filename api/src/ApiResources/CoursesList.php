<?php

namespace App\ApiResources;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\PaymentCourseController;

#[ApiResource]
#[Post(
    uriTemplate: '/course/buy/',
    controller: PaymentCourseController::class,
    read: false,
    name: 'buy'
)]
class CoursesList
{
    private $product_id;

    public function setProductid($product_id) {
        $this->product_id = $product_id;
    }

    public function getProductId() {
        return $this->product_id;
    }
}
