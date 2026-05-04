# RailConnect France — Version PHP + MySQL

## Structure du projet

```
railconnect_php/
├── config/
│   └── db.php              ← Connexion PDO à MySQL
├── includes/
│   ├── header.php          ← Navigation commune
│   └── footer.php          ← Pied de page commun
├── api/
│   ├── stations.php        ← API JSON /api/stations.php
│   └── horaires.php        ← API JSON /api/horaires.php?station=Paris&type=rapide
├── home.php                ← Page d'accueil
├── station.php             ← Stations (données depuis BDD)
├── hor.php                 ← Horaires (données depuis BDD)
├── tarifs.php              ← Tarifs
├── contact.php             ← Contact
├── database.sql            ← Script SQL (CREATE + INSERT)
└── *.jpg                   ← Images des stations
```

## Installation

### 1. Base de données

```bash
mysql -u root -p < database.sql
```

Cela crée la base `projet_web` avec les tables `stations` et `horaires` et y insère toutes les données.

### 2. Configuration

Éditez `config/db.php` pour adapter les identifiants :

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'projet_web');
define('DB_USER', 'root');   // votre utilisateur MySQL
define('DB_PASS', '');       // votre mot de passe MySQL
```

### 3. Serveur web

Placez le dossier dans votre répertoire web (ex: `htdocs/` pour XAMPP, `www/` pour WAMP) et accédez via :

```
http://localhost/railconnect_php/home.php
```

Ou avec le serveur intégré PHP :

```bash
cd railconnect_php
php -S localhost:8000
```
Puis ouvrir : http://localhost:8000/home.php

## API JSON

Les données sont aussi accessibles via API :

| Endpoint | Description |
|---|---|
| `GET /api/stations.php` | Toutes les stations |
| `GET /api/horaires.php` | Tous les horaires |
| `GET /api/horaires.php?station=Paris` | Filtrer par station |
| `GET /api/horaires.php?type=rapide` | Filtrer par type |
| `GET /api/horaires.php?classe=premiere` | Filtrer par classe |

## Tables MySQL

### `stations`
| Colonne | Type | Description |
|---|---|---|
| id | INT | Clé primaire |
| nom | VARCHAR(100) | Nom complet de la gare |
| slug | VARCHAR(100) | Identifiant URL (ex: paris-gare-de-lyon) |
| ville | VARCHAR(100) | Ville |
| description | TEXT | Description |
| image | VARCHAR(255) | Fichier image |
| latitude / longitude | DECIMAL | Coordonnées GPS |
| wifi, guichets, parking… | TINYINT(1) | Services disponibles (0/1) |

### `horaires`
| Colonne | Type | Description |
|---|---|---|
| id | INT | Clé primaire |
| station_depart | VARCHAR(100) | Ville de départ |
| station_arrivee | VARCHAR(100) | Ville d'arrivée |
| heure_depart / heure_arrivee | TIME | Horaires |
| numero_train | VARCHAR(20) | Numéro du train (ex: TGV 101) |
| quai | INT | Numéro de quai |
| type_train | ENUM | rapide / express / regional |
| classe | ENUM | premiere / seconde |
| retard | INT | Retard en minutes |
