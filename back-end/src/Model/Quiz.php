<?php

namespace App\Model;

class Quiz
{
    private $id;
    private $title;
    private $userId;

    public function __construct($id, $title, $userId)
    {
        $this->id = $id;
        $this->title = $title;
        $this->userId = $userId;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getUserId()
    {
        return $this->userId;
    }
}
?>