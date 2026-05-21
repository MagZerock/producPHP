<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Controller\ProductController;

$action = $_GET['action'] ?? '';
$page   = $_GET['page'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'create') {
        $controller = new ProductController();
        $controller->create($_POST);
    } else {
        header("HTTP/1.1 400 Bad Request");
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid action']);
    }
    exit;
}

if ($action === 'list') {
    $controller = new ProductController();
    $controller->list();
    exit;
}

if ($page === 'add_product') {
    include __DIR__ . '/app/view/add_product.html';
} else {
    include __DIR__ . '/app/view/view_products.html';
}
