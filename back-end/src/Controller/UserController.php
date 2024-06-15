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
        // Hacher le mot de passe avant de le stocker
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO user (name, email, password) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$name, $email, $hashedPassword]);
    }

    public function loginUser($email, $password)
    {
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return new User($user['id'], $user['name'], $user['email'], $user['password']);
        } else {
            return null;
        }
    }
}
?>