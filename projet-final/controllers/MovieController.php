<?php

require_once __DIR__ . '/../services/TMDBService.php';

class MovieController {

    public static function list($type) {
        try {
            $movies = TMDBService::getMovies($type);
            http_response_code(200);
            echo json_encode($movies, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public static function search($query) {
        if (empty(trim($query))) {
            http_response_code(400);
            echo json_encode(['error' => "Paramètre 'q' requis."]);
            return;
        }
        try {
            $results = TMDBService::search($query);
            http_response_code(200);
            echo json_encode($results, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public static function show($id) {
        try {
            $movie = TMDBService::getById($id);
            http_response_code(200);
            echo json_encode($movie, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            http_response_code(404);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
