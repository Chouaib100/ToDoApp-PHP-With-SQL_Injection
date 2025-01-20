<?php
// config/database.php

$host = 'localhost';          // Hôte de la base de données
$dbname = 'todo_app';         // Nom de la base de données
$username = 'root';           // Nom d'utilisateur MySQL
$password = 'Chouaib2004';               // Mot de passe MySQL

try {
    // Connexion à la base de données avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Activer les exceptions pour les erreurs
} catch (PDOException $e) {
    // En cas d'erreur, afficher un message et arrêter le script
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>