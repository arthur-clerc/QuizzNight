<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=QuizzNight;unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock', 'root', 'root', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie à la base de données!<br>";
} catch (PDOException $e) {
    die('Échec de la connexion : ' . $e->getMessage());
}

require_once __DIR__ . '/../src/routes.php';
?>