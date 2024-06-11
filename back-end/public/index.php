<?php

use App\Controller\QuizController;

require_once '../vendor/autoload.php';

// Headers pour gérer les CORS (Cross-Origin Resource Sharing)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$pathInfo = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : [];

$controller = new QuizController();
switch ($requestMethod) {
    case 'GET':
        if (isset($pathInfo[0]) && $pathInfo[0] === 'quizzes') {
            echo json_encode($controller->listQuizzes());
        }
        break;
    case 'POST':
        // Handle POST request
        break;
    // Handle other HTTP methods
}
?>

