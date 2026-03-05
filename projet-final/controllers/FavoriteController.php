<?php

class FavoriteController {

    private static function load() {
        if (!file_exists(FAVORITES_FILE)) {
            return [];
        }
        $data = file_get_contents(FAVORITES_FILE);
        return json_decode($data, true) ?? [];
    }

    private static function save($favorites) {
        $dir = dirname(FAVORITES_FILE);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents(FAVORITES_FILE, json_encode($favorites, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public static function index() {
        $favorites = self::load();
        http_response_code(200);
        echo json_encode($favorites, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public static function store() {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        if (!$data || !isset($data['id'], $data['title'])) {
            http_response_code(400);
            echo json_encode(['error' => "Les champs 'id' et 'title' sont requis."]);
            return;
        }

        $favorites = self::load();

        foreach ($favorites as $fav) {
            if ((int)$fav['id'] === (int)$data['id']) {
                http_response_code(409);
                echo json_encode(['error' => "Ce film est déjà dans les favoris."]);
                return;
            }
        }

        $newFav = [
            'id'          => (int)$data['id'],
            'title'       => $data['title'],
            'poster_path' => $data['poster_path'] ?? null,
            'year'        => $data['year'] ?? null,
            'added_at'    => date('Y-m-d H:i:s'),
        ];

        $favorites[] = $newFav;
        self::save($favorites);

        http_response_code(201);
        echo json_encode(['message' => "Favori ajouté.", 'favorite' => $newFav]);
    }

    public static function destroy($id) {
        $favorites = self::load();
        $initial   = count($favorites);

        $favorites = array_values(array_filter($favorites, function ($fav) use ($id) {
            return (int)$fav['id'] !== (int)$id;
        }));

        if (count($favorites) === $initial) {
            http_response_code(404);
            echo json_encode(['error' => "Favori introuvable."]);
            return;
        }

        self::save($favorites);
        http_response_code(200);
        echo json_encode(['message' => "Favori supprimé."]);
    }
}
