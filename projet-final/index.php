<?php

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controllers/MovieController.php';
require_once __DIR__ . '/controllers/FavoriteController.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

header("Content-Type: application/json; charset=utf-8");

$method = $_SERVER['REQUEST_METHOD'];
$path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path   = rtrim($path, '/');

if ($method === 'GET' && ($path === '' || $path === '/')) {
    http_response_code(200);
    echo json_encode([
        "message" => "API Films opérationnelle",
        "routes"  => [
            "GET  /movies?type=popular"    => "Films par catégorie",
            "GET  /movies/search?q=batman" => "Recherche de films",
            "GET  /movies/{id}"            => "Détail d'un film",
            "GET  /favorites"              => "Liste des favoris",
            "POST /favorites"              => "Ajouter un favori",
            "DELETE /favorites/{id}"       => "Supprimer un favori",
        ]
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

if ($method === 'GET' && $path === '/movies/search') {
    $query = $_GET['q'] ?? '';
    MovieController::search($query);
    exit;
}

if ($method === 'GET' && preg_match('#^/movies/(\d+)$#', $path, $matches)) {
    MovieController::show((int)$matches[1]);
    exit;
}

if ($method === 'GET' && $path === '/movies') {
    $type = $_GET['type'] ?? 'popular';
    MovieController::list($type);
    exit;
}

if ($method === 'GET' && $path === '/favorites') {
    FavoriteController::index();
    exit;
}

if ($method === 'POST' && $path === '/favorites') {
    FavoriteController::store();
    exit;
}

if ($method === 'DELETE' && preg_match('#^/favorites/(\d+)$#', $path, $matches)) {
    FavoriteController::destroy((int)$matches[1]);
    exit;
}

http_response_code(404);
echo json_encode(['error' => "Route inconnue : $method $path"]);
