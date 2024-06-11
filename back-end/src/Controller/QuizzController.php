<?php
namespace App\Controller;

use App\Model\Quizz;

class QuizController {
    public function listQuizzes() {
        $quizzModel = new Quizz();
        return $quizzModel->getAllQuizzes();
    }
}
?>