<?php

class TMDBService {

    public static function getMovies($type) {
        $allowed = ['popular', 'top_rated', 'upcoming', 'now_playing'];

        if (!in_array($type, $allowed, true)) {
            throw new Exception("Type invalide. Valeurs acceptées : " . implode(', ', $allowed));
        }

        $url = TMDB_BASE_URL . "/movie/$type?api_key=" . TMDB_API_KEY . "&language=fr-FR";
        $response = @file_get_contents($url);

        if ($response === false) {
            throw new Exception("Impossible de contacter l'API TMDB. Vérifie l'extension OpenSSL de PHP.");
        }

        return json_decode($response, true);
    }

    public static function search($query) {
        $url = TMDB_BASE_URL . "/search/movie?api_key=" . TMDB_API_KEY . "&language=fr-FR&query=" . urlencode($query);
        $response = @file_get_contents($url);

        if ($response === false) {
            throw new Exception("Impossible de contacter l'API TMDB.");
        }

        return json_decode($response, true);
    }

    public static function getById($id) {
        $url = TMDB_BASE_URL . "/movie/$id?api_key=" . TMDB_API_KEY . "&language=fr-FR";
        $response = @file_get_contents($url);

        if ($response === false) {
            throw new Exception("Impossible de contacter l'API TMDB.");
        }

        $data = json_decode($response, true);

        if (isset($data['success']) && $data['success'] === false) {
            throw new Exception("Film introuvable.");
        }

        return $data;
    }
}
