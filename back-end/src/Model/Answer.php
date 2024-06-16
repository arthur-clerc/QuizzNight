<?php

namespace App\Model;

class Answer
{
    public function __construct(
        public int $id,
        public int $questionId,
        public string $answerText,
        public bool $isCorrect
    ) {}

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getQuestionId(): int
    {
        return $this->questionId;
    }

    public function getAnswerText(): string
    {
        return $this->answerText;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }
}
?>