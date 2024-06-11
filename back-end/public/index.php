<?php
require_once '../vendor/autoload.php';

use App\Controller\QuizzController;

$controller = new QuizzController();
$controller->handleRequest();
?>
