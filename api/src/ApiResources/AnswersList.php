<?php

namespace App\ApiResources;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\SubmitQuizController;

#[ApiResource]
#[Post(
    uriTemplate: '/quiz/submit/',
    controller: SubmitQuizController::class,
    read: false,
    deserialize: false,
    name: 'submit_quiz'
)]
class AnswersList
{
    private $productId;

    public function setProductId($id) {
        $this->productId = $id;
    }

    public function getProductId() {
        return $this->productId;
    }
}
