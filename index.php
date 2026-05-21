<?php

$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$assetPath = __DIR__ . $requestPath;

$extension = strtolower(pathinfo($assetPath, PATHINFO_EXTENSION));
$contentTypeMap = [
    'css' => 'text/css; charset=UTF-8',
    'js' => 'application/javascript; charset=UTF-8',
    'json' => 'application/json; charset=UTF-8',
    'svg' => 'image/svg+xml',
    'png' => 'image/png',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'gif' => 'image/gif',
    'ico' => 'image/x-icon',
];

if (is_file($assetPath) && isset($contentTypeMap[$extension])) {
    header('Content-Type: ' . $contentTypeMap[$extension]);
    readfile($assetPath);
    exit;
}

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
