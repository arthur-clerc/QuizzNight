<?php

namespace App\Controller;

use PDO;
use App\Model\Quizz;

class QuizzController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllQuizzes()
    {
        $sql = "SELECT * FROM quizz";
        $stmt = $this->pdo->query($sql);
        $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($quizzes as $quiz) {
            $result[] = new Quizz($quiz['id'], $quiz['title'], $quiz['user_id']);
        }

        return $result;
    }

    public function getQuizzById($id)
    {
        $sql = "SELECT * FROM quizz WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($quiz) {
            return new Quizz($quiz['id'], $quiz['title'], $quiz['user_id']);
        } else {
            return null;
        }
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