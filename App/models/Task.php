<?php
// app/models/Task.php

class Task {
    private $pdo;

    // Constructeur pour initialiser la connexion à la base de données
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Méthode pour récupérer toutes les tâches d'un utilisateur
    public function getAll($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    // Méthode pour ajouter une tâche
    public function create($user_id, $description) {
        $stmt = $this->pdo->prepare("INSERT INTO tasks (user_id, description) VALUES (?, ?)");
        $stmt->execute([$user_id, $description]);
    }

    // Méthode pour supprimer une tâche
    public function delete($task_id, $user_id) {
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
        $stmt->execute([$task_id, $user_id]);
    }
}
?>