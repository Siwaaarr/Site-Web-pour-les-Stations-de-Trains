<?php
// =====================================================
// config/db.php — Connexion à la base de données
// Style du cours Dr. Rim Zarrouk (PDO)
// =====================================================

$host    = "localhost";
$dbname  = "projet_web";
$user    = "root";
$pass    = "";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
