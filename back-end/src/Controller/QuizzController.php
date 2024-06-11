<?php
namespace App\Controller;
use App\Model\Quizz;

class QuizzController {
    public function handleRequest() {
        // Logique pour gérer les requêtes
    }

    public function listQuizzes() {
        $quizzModel = new Quizz();
        $quizzes = $quizzModel->getAllQuizzes();
    }
}
?>
