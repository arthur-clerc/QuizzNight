<?php

namespace App\Controller;

use PDO;
use App\Model\User;

class UserController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function registerUser($name, $email, $password)
    {
        $sql = "INSERT INTO user (name, email, password) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$name, $email, $password]);
    }

    public function loginUser($email, $password)
    {
        $sql = "SELECT * FROM user WHERE email = ? AND password = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email, $password]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            return new User($user['id'], $user['name'], $user['email'], $user['password']);
        } else {
            return null;
        }
    }
}

?>