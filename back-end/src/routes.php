<?php

use App\Controller\UserController;
use App\Controller\QuizzController;

// Fonction route générique
function route($method, $path, $callback, $pdo) {
    static $routeFound = false;
    if (!$routeFound && $_SERVER['REQUEST_METHOD'] === $method && preg_match("@^$path$@", $_SERVER['REQUEST_URI'], $matches)) {
        $routeFound = true;
        $callback($matches, $pdo);
    }
}

// Routes pour les utilisateurs
route('POST', '/api/register', function($matches, $pdo) {
    $controller = new UserController($pdo);
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->registerUser($data['name'], $data['email'], $data['password']);
}, $pdo);

route('POST', '/api/login', function($matches, $pdo) {
    $controller = new UserController($pdo);
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->loginUser($data['email'], $data['password']);
}, $pdo);

// Routes pour les quizzes
route('GET', '/api/quizzes', function($matches, $pdo) {
    try {
        $controller = new QuizzController($pdo);
        error_log("QuizzController instantiated");
        $quizzes = $controller->getAllQuizzes();
        error_log("getAllQuizzes called");
        echo json_encode($quizzes);
    } catch (Exception $e) {
        error_log("Error in route: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}, $pdo);

route('GET', '/api/quiz/(\d+)', function($matches, $pdo) {
    $quizId = $matches[1];
    $controller = new QuizzController($pdo);
    $quiz = $controller->getQuizzById($quizId);
    if ($quiz) {
        echo json_encode($quiz);
    } else {
        http_response_code(404);
    }
}, $pdo);

route('POST', '/api/quiz', function($matches, $pdo) {
    $controller = new QuizzController($pdo);
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->createQuizz($data['title'], $data['user_id']);
}, $pdo);

route('PUT', '/api/quiz/(\d+)', function($matches, $pdo) {
    $quizId = $matches[1];
    $controller = new QuizzController($pdo);
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->updateQuizz($quizId, $data['title']);
}, $pdo);
?>