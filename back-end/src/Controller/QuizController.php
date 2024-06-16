<?php

namespace App\Controller;

use PDO;
use Exception;
use App\Model\Quiz;
use App\Model\Question;
use App\Model\Answer;

class QuizController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllQuizzes()
    {
        $sql = "SELECT q.id AS quiz_id, q.title, qu.id AS question_id, qu.question_text, a.id AS answer_id, a.answer_text, a.is_correct
                FROM quizz q
                LEFT JOIN question qu ON q.id = qu.quizz_id
                LEFT JOIN answer a ON qu.id = a.question_id";
        $stmt = $this->pdo->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $quizzes = [];

        foreach ($data as $row) {
            $quizId = $row['quiz_id'];
            $questionId = $row['question_id'];
            $answerId = $row['answer_id'];

            if (!isset($quizzes[$quizId])) {
                $quizzes[$quizId] = [
                    'id' => $quizId,
                    'title' => $row['title'],
                    'questions' => []
                ];
            }

            $quiz = &$quizzes[$quizId];

            if ($questionId) {
                if (!isset($quiz['questions'][$questionId])) {
                    $quiz['questions'][$questionId] = [
                        'id' => $questionId,
                        'question_text' => $row['question_text'],
                        'answers' => []
                    ];
                }

                $question = &$quiz['questions'][$questionId];

                if ($answerId) {
                    $question['answers'][] = [
                        'id' => $answerId,
                        'answer_text' => $row['answer_text'],
                        'is_correct' => $row['is_correct'] == 1 // Conversion en booléen
                    ];
                }
            }
        }

        // Convertir les tableaux en objets Quiz, Question et Answer
        foreach ($quizzes as &$quiz) {
            $quizObj = new Quiz($quiz['id'], $quiz['title'], null);
            foreach ($quiz['questions'] as $question) {
                $questionObj = new Question($question['id'], $quizObj->getId(), $question['question_text']);
                foreach ($question['answers'] as $answer) {
                    $answerObj = new Answer($answer['id'], $questionObj->getId(), $answer['answer_text'], $answer['is_correct']);
                    $questionObj->addAnswer($answerObj);
                }
                $quizObj->addQuestion($questionObj);
            }
            $quiz = $quizObj;
        }

        return array_values($quizzes);
    }

    public function getQuizzById($id)
    {
        $sql = "SELECT q.id AS quiz_id, q.title, qu.id AS question_id, qu.question_text, a.id AS answer_id, a.answer_text, a.is_correct
                FROM quizz q
                LEFT JOIN question qu ON q.id = qu.quizz_id
                LEFT JOIN answer a ON qu.id = a.question_id
                WHERE q.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $quiz = null;

        foreach ($data as $row) {
            $quizId = $row['quiz_id'];
            $questionId = $row['question_id'];
            $answerId = $row['answer_id'];

            if (!$quiz) {
                $quiz = new Quiz($quizId, $row['title'], null);
            }

            if ($questionId) {
                if (!isset($quiz->getQuestions()[$questionId])) {
                    $question = new Question($questionId, $quizId, $row['question_text']);
                    $quiz->addQuestion($question);
                } else {
                    $question = $quiz->getQuestions()[$questionId];
                }

                if ($answerId) {
                    $answer = new Answer($answerId, $questionId, $row['answer_text'], $row['is_correct'] == 1); // Conversion en booléen
                    $question->addAnswer($answer);
                }
            }
        }

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
