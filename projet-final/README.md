# AFILM — API REST Films

API RESTful en PHP qui récupère des films depuis TMDB et gère une liste de favoris.

## Installation

```bash
# 1. Cloner / dézipper le projet
cd -Project-API-Film-final

# 2. Lancer le serveur PHP
php -S localhost:8000

# 3. Ouvrir le frontend
# http://localhost:8000
```

## Structure

```
projet-final/
├── index.php                  → Router principal
├── front.html                 → Interface web
├── config/
│   └── config.php             → Clé API TMDB
├── services/
│   └── TMDBService.php        → Appels à l'API TMDB
├── controllers/
│   ├── MovieController.php    → Gestion des films
│   └── FavoriteController.php → Gestion des favoris
└── data/
    └── favorites.json         → Stockage des favoris
```

## Routes disponibles

| Méthode | Route                     | Description               |
|---------|---------------------------|---------------------------|
| GET     | `/`                       | Message de bienvenue      |
| GET     | `/movies?type=popular`    | Films par catégorie       |
| GET     | `/movies/search?q=batman` | Recherche par titre       |
| GET     | `/movies/{id}`            | Détail d'un film          |
| GET     | `/favorites`              | Liste des favoris         |
| POST    | `/favorites`              | Ajouter un favori         |
| DELETE  | `/favorites/{id}`         | Supprimer un favori       |

Types disponibles : `popular`, `top_rated`, `upcoming`, `now_playing`

## Exemples de requêtes Postman

**GET films populaires**
```
GET http://localhost:8000/movies?type=popular
```

**GET recherche**
```
GET http://localhost:8000/movies/search?q=inception
```

**GET film par ID**
```
GET http://localhost:8000/movies/27205
```

**GET favoris**
```
GET http://localhost:8000/favorites
```

**POST ajouter un favori**
```
POST http://localhost:8000/favorites
Content-Type: application/json

{
  "id": 27205,
  "title": "Inception",
  "poster_path": "/oYuLEt3zVCKq57qu2F8dT7NIa6f.jpg",
  "year": "2010"
}
```

**DELETE supprimer un favori**
```
DELETE http://localhost:8000/favorites/27205
```

## Codes HTTP retournés

- `200` OK
- `201` Créé (ajout favori)
- `400` Mauvaise requête (paramètre manquant ou invalide)
- `404` Ressource introuvable
- `409` Conflit (favori déjà existant)
- `500` Erreur serveur
