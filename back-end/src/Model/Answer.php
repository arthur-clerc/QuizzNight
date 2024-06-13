<?php

namespace App\Model;

class Answer
{
    private $id;
    private $questionId;
    private $answerText;
    private $isCorrect;

    public function __construct($id, $questionId, $answerText, $isCorrect)
    {
        $this->id = $id;
        $this->questionId = $questionId;
        $this->answerText = $answerText;
        $this->isCorrect = $isCorrect;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getQuestionId()
    {
        return $this->questionId;
    }

    public function getAnswerText()
    {
        return $this->answerText;
    }

    public function getIsCorrect()
    {
        return $this->isCorrect;
    }
}
?>