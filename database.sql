-- =====================================================
-- Base de données : projet_web
-- RailConnect France - Stations & Horaires
-- =====================================================

CREATE DATABASE IF NOT EXISTS projet_web
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE projet_web;

-- -----------------------------------------------
-- Table : stations
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS stations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    ville VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    latitude DECIMAL(9,6),
    longitude DECIMAL(9,6),
    wifi TINYINT(1) DEFAULT 0,
    guichets TINYINT(1) DEFAULT 0,
    parking TINYINT(1) DEFAULT 0,
    restaurant TINYINT(1) DEFAULT 0,
    boutiques TINYINT(1) DEFAULT 0,
    cafe TINYINT(1) DEFAULT 0,
    bornes_auto TINYINT(1) DEFAULT 0,
    toilettes TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -----------------------------------------------
-- Table : horaires
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS horaires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    station_depart VARCHAR(100) NOT NULL,
    station_arrivee VARCHAR(100) NOT NULL,
    heure_depart TIME NOT NULL,
    heure_arrivee TIME NOT NULL,
    numero_train VARCHAR(20) NOT NULL,
    quai INT,
    type_train ENUM('rapide','express','regional') DEFAULT 'express',
    classe ENUM('premiere','seconde') DEFAULT 'seconde',
    retard INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -----------------------------------------------
-- Données : stations
-- -----------------------------------------------
INSERT INTO stations (nom, slug, ville, description, image, latitude, longitude, wifi, guichets, parking, restaurant, boutiques, cafe, bornes_auto, toilettes) VALUES
('Paris Gare de Lyon',     'paris-gare-de-lyon',     'Paris',    'Station principale de Paris avec connexions nationales et internationales', 'paris-gare-de-lyon.jpg',    48.844300,  2.374800, 1, 1, 1, 0, 0, 1, 0, 1),
('Lyon Part-Dieu',         'lyon-part-dieu',          'Lyon',     'Hub ferroviaire majeur du sud-est de la France',                            'lyon-part-dieu.jpg',        45.760100,  4.859900, 1, 1, 0, 1, 0, 0, 0, 1),
('Marseille Saint-Charles','marseille-saint-charles', 'Marseille','Porte d\'entrée vers la Méditerranée et la Provence',                       'marseille-st-charles.jpg',  43.303100,  5.380000, 0, 0, 1, 0, 1, 0, 0, 1),
('Bordeaux Saint-Jean',    'bordeaux-saint-jean',     'Bordeaux', 'Station moderne au cœur de la région viticole',                            'saint-jean.jpg',            44.825300, -0.556000, 1, 0, 0, 0, 0, 1, 1, 0),
('Nice Ville',             'nice-ville',              'Nice',     'Gare principale de la Côte d\'Azur',                                        'nice-ville.jpg',            43.704500,  7.261300, 1, 1, 1, 0, 1, 1, 0, 1),
('Marseille Saint-Denis',  'marseille-saint-denis',   'Marseille','Gare secondaire de Marseille desservant le nord',                          'marseille-saint-denis.jpg', 43.315000,  5.375000, 0, 1, 0, 0, 0, 0, 1, 1);

-- -----------------------------------------------
-- Données : horaires
-- -----------------------------------------------
INSERT INTO horaires (station_depart, station_arrivee, heure_depart, heure_arrivee, numero_train, quai, type_train, classe, retard) VALUES
('Paris',    'Lyon',      '08:30', '10:45', 'TGV 101', 3, 'rapide',   'premiere', 0),
('Paris',    'Marseille', '09:30', '12:30', 'TGV 205', 2, 'regional', 'seconde',  15),
('Lyon',     'Paris',     '11:00', '13:15', 'TGV 320', 1, 'express',  'premiere', 0),
('Paris',    'Bordeaux',  '14:00', '16:00', 'TGV 410', 4, 'rapide',   'seconde',  0),
('Lyon',     'Marseille', '16:20', '18:00', 'TGV 520', 2, 'express',  'premiere', 0),
('Marseille','Paris',     '07:45', '10:30', 'TGV 612', 1, 'rapide',   'seconde',  5),
('Bordeaux', 'Lyon',      '10:15', '13:45', 'TER 714', 3, 'regional', 'seconde',  0),
('Lille',    'Paris',     '12:30', '14:00', 'TGV 815', 2, 'express',  'premiere', 10),
('Paris',    'Nice',      '06:55', '11:20', 'TGV 6001',5, 'rapide',   'premiere', 0),
('Nice',     'Paris',     '13:10', '17:35', 'TGV 6002',1, 'rapide',   'seconde',  0),
('Bordeaux', 'Paris',     '07:00', '09:00', 'TGV 403', 1, 'rapide',   'premiere', 0),
('Marseille','Lyon',      '09:00', '10:40', 'TGV 521', 3, 'express',  'seconde',  0),
('Lyon',     'Bordeaux',  '15:00', '18:30', 'TER 720', 4, 'regional', 'seconde',  0),
('Paris',    'Lille',     '11:00', '12:30', 'TGV 820', 6, 'express',  'premiere', 0);

-- -----------------------------------------------
-- Table : contacts (messages du formulaire)
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS contacts (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nom        VARCHAR(150)  NOT NULL,
    email      VARCHAR(255)  NOT NULL,
    sujet      VARCHAR(255)  NOT NULL,
    message    TEXT          NOT NULL,
    lu         TINYINT(1)    DEFAULT 0,
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
