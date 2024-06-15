<?php

namespace App\Controller;

use PDO;
use Exception;
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
        $sql = "SELECT q.id, q.title, qu.id as question_id, qu.question_text, a.id as answer_id, a.answer_text, a.is_correct
                FROM quizz q
                LEFT JOIN question qu ON q.id = qu.quizz_id
                LEFT JOIN answer a ON qu.id = a.question_id";
        $stmt = $this->pdo->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $quizzes = [];
        
        foreach ($data as $row) {
            $quizId = $row['id'];
            $questionId = $row['question_id'];
            $answerId = $row['answer_id'];
            
            if (!isset($quizzes[$quizId])) {
                $quizzes[$quizId] = [
                    'id' => $quizId,
                    'title' => $row['title'],
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

        return array_values($quizzes);
    }  

    public function getQuizzById($id)
    {
        $sql = "SELECT * FROM quizz WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $quiz = json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        return $quiz;
    }

    public function createQuizz($title, $userId, $questions)
    {
        try {
            $this->pdo->beginTransaction();
            $sql = "INSERT INTO quizz (title, user_id) VALUES (?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$title, $userId]);
            $quizId = $this->pdo->lastInsertId();

            foreach ($questions as $question) {
                $sql = "INSERT INTO question (quizz_id, question_text) VALUES (?, ?)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$quizId, $question['question_text']]);
                $questionId = $this->pdo->lastInsertId();

                foreach ($question['answers'] as $answer) {
                    $sql = "INSERT INTO answer (question_id, answer_text, is_correct) VALUES (?, ?, ?)";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([$questionId, $answer['answer_text'], $answer['is_correct']]);
                }
            }
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function updateQuizz($id, $title)
    {
        $sql = "UPDATE quizz SET title = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$title, $id]);
    }
}
?>