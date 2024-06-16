<?php

namespace App\Model;

class Question
{
    public function __construct(
        public int $id,
        public int $quizId,
        public string $questionText,
        public array $answers = []
    ) {}

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getQuizId(): int
    {
        return $this->quizId;
    }

    public function getQuestionText(): string
    {
        return $this->questionText;
    }

    public function getAnswers(): array
    {
        return $this->answers;
    }

    // Method to add an answer
    public function addAnswer(Answer $answer): void
    {
        $this->answers[$answer->getId()] = $answer;
    }
}
?>
