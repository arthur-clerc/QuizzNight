<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=QuizzNight;unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock', 'root', 'root');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Inclusion des routes
require_once __DIR__ . '/../src/routes.php';
?>