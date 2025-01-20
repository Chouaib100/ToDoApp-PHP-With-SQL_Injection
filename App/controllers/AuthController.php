<?php
// app/controllers/AuthController.php

require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $user;

    // Constructeur pour initialiser le modèle User
    public function __construct($pdo) {
        $this->user = new User($pdo);
    }

    // Méthode pour gérer la connexion
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $this->user->login($username, $password);
            if ($user) {
                // Démarrer la session et rediriger vers le tableau de bord
                session_start();
                $_SESSION['user_id'] = $user['id'];
                header('Location: /todo-app/public/index.php?action=dashboard');
                exit;
            } else {
                echo "Identifiants incorrects.";
            }
        }
        require __DIR__ . '/../views/auth/login.php';
    }

    // Méthode pour gérer l'inscription
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $this->user->register($username, $email, $password);
            header('Location: /todo-app/public/index.php?action=login');
            exit;
        }
        require __DIR__ . '/../views/auth/register.php';
    }
}
?>