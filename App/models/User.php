<?php
// app/models/User.php

class User {
    private $pdo;

    // Constructeur pour initialiser la connexion à la base de données
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Méthode pour enregistrer un nouvel utilisateur
    public function register($username, $email, $password) {
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, password_hash($password, PASSWORD_BCRYPT)]);
    }

    // Méthode pour connecter un utilisateur
    public function login($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Vérifier le mot de passe
        if ($user && password_verify($password, $user['password'])) {
            return $user; // Retourner les données de l'utilisateur
        }
        return false; // Identifiants incorrects
    }


    public function getUserById($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    }


    public function updateUser($user_id, $update_data) {
        $fields = [];
        $values = [];

        foreach ($update_data as $field => $value) {
            $fields[] = "$field = ?";
            $values[] = $value;
        }

        $values[] = $user_id;

        $query = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
    }
}
?>