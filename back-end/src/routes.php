<?php

use App\Controller\UserController;
use App\Controller\QuizController;

header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

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
    if (!isset($data['name'], $data['email'], $data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }
    $controller->registerUser($data['name'], $data['email'], $data['password']);
    echo json_encode(['success' => 'User registered successfully']);
}, $pdo);

route('POST', '/api/login', function($matches, $pdo) {
    $controller = new UserController($pdo);
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['email'], $data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }
    
    $user = $controller->loginUser($data['email'], $data['password']);
    
    if ($user) {
        echo json_encode(['success' => 'Login successful', 'user' => $user->toArray()]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
    }
}, $pdo);

// Routes pour les quizzes
route('GET', '/api/quizzes', function($matches, $pdo) {
    try {
        $controller = new QuizController($pdo);
        $quizzes = $controller->getAllQuizzes();
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: http://localhost:5173');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        echo json_encode($quizzes);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}, $pdo);


route('GET', '/api/quiz/(\d+)', function($matches, $pdo) {
    $quizId = $matches[1];
    $controller = new QuizController($pdo);
    $quiz = $controller->getQuizzById($quizId);
    if ($quiz) {
        echo json_encode($quiz);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Quiz not found']);
    }
}, $pdo);

route('POST', '/api/quiz', function($matches, $pdo) {
    $controller = new QuizController($pdo);
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->createQuizz($data['title'], $data['user_id'], $data['questions']);
}, $pdo);

route('PUT', '/api/quiz/(\d+)', function($matches, $pdo) {
    $quizId = $matches[1];
    $controller = new QuizController($pdo);
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->updateQuizz($quizId, $data['title']);
}, $pdo);
?>