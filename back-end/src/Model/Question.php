<?php

namespace App\Model;

class Question
{
    private $id;
    private $quizzId;
    private $questionText;

    public function __construct($id, $quizzId, $questionText)
    {
        $this->id = $id;
        $this->quizzId = $quizzId;
        $this->questionText = $questionText;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getQuizzId()
    {
        return $this->quizzId;
    }

    public function getQuestionText()
    {
        return $this->questionText;
    }
}
?>