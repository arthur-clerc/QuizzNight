<?php

namespace App\Controller;

use PDO;
use App\Model\Quiz;

class QuizController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllQuizzes()
    {
        $sql ="
            SELECT 
            q.id AS quizz_id, 
            q.title AS quizz_title,
            qu.id AS question_id, 
            qu.question_text,
            a.id AS answer_id, 
            a.answer_text, 
            a.is_correct
            FROM quizz q
            LEFT JOIN question qu ON q.id = qu.quizz_id
            LEFT JOIN answer a ON qu.id = a.question_id
        ";
        $stmt = $this->pdo->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $quizzes = [];
        foreach ($results as $row) {
            $quizId = $row['quizz_id'];
            $questionId = $row['question_id'];
            $answerId = $row['answer_id'];
    
            if (!isset($quizzes[$quizId])) {
                $quizzes[$quizId] = [
                    'id' => $quizId,
                    'title' => $row['quizz_title'],
                    'questions' => []
                ];
            }
    
            if ($questionId) {
                if (!isset($quizzes[$quizId]['questions'][$questionId])) {
                    $quizzes[$quizId]['questions'][$questionId] = [
                        'id' => $questionId,
                        'question_text' => $row['question_text'],
                        'answers' => []
                    ];
                }
    
                if ($answerId) {
                    $quizzes[$quizId]['questions'][$questionId]['answers'][$answerId] = [
                        'id' => $answerId,
                        'answer_text' => $row['answer_text'],
                        'is_correct' => $row['is_correct']
                    ];
                }
            }
        }
    
        // Convertir en JSON
        $quizzes = json_encode(array_values($quizzes));
        return $quizzes;
    }    

    public function getQuizzById($id)
    {
        $sql = "SELECT * FROM quizz WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $quiz = json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        return $quiz;
    }

    public function createQuizz($title, $userId)
    {
        $sql = "INSERT INTO quizz (title, user_id) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$title, $userId]);
    }

    public function updateQuizz($id, $title)
    {
        $sql = "UPDATE quizz SET title = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$title, $id]);
    }
}
?>