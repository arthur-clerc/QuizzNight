<?php

namespace App\Model;

class Quiz
{
    public function __construct(
        public int $id,
        public string $title,
        public ?int $userId = null,
        public array $questions = []
    ) {}

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getQuestions(): array
    {
        return $this->questions;
    }

    // Method to add a question
    public function addQuestion(Question $question): void
    {
        $this->questions[$question->getId()] = $question;
    }
}
?>
