<?php

namespace App\Controller;

use App\ApiResources\AnswersList;
use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class SubmitQuizController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager) {

    }

    public function __invoke(AnswersList $answersList, RequestStack $requestStack) {
        $request = $requestStack->getCurrentRequest()->getContent();
        $result_tab = json_decode($request);
        $nbGoodAnswers = 0;
        $correctionTab = [];
        foreach($result_tab as $key => $value) {
            /**
             * @var Question $question
             */
            $question = $this->manager->getRepository(Question::class)->find($key);
            $goodAnswer = $question->getGoodAnswer();
            $answers = json_decode($question->getSettings());
            $correctionTab[] = ['question' => $answers->content, 'yourAnswer' => $answers->answers[$value - 1], 'goodAnswer' => $answers->answers[$goodAnswer]->label];
            if($goodAnswer == $value - 1) {
                $nbGoodAnswers += 1;
            }
        }
        $score = $nbGoodAnswers * 100 / count((array) $result_tab);

        return new JsonResponse(['score' => $score, 'correction' => $correctionTab], '200');
    }
}
