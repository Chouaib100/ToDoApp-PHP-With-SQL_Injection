<?php
// Inclure le modèle User pour interagir avec la base de données
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/database.php';

class ProfileController {
    private $userModel;

    // Constructeur pour initialiser le modèle User
    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

    // Afficher le formulaire d'édition du profil
    public function edit() {
        session_start();

        // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: /todo-app/public/index.php?action=login');
            exit;
        }

        // Récupérer les informations de l'utilisateur connecté
        $user = $this->userModel->getUserById($_SESSION['user_id']);

        // Inclure la vue pour éditer le profil
        require __DIR__ . '/../views/profile/edit.php';
    }

    // Mettre à jour le profil utilisateur
    public function update() {
        session_start();

        // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: /todo-app/public/index.php?action=login');
            exit;
        }

        // Vérifier si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = htmlspecialchars($_POST['username']);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];

            // Récupérer les informations de l'utilisateur connecté
            $user = $this->userModel->getUserById($_SESSION['user_id']);

            // Vérifier le mot de passe actuel
            if (password_verify($current_password, $user['password'])) {
                // Préparer les données à mettre à jour
                $update_data = [
                    'username' => $username,
                    'email' => $email
                ];

                // Mettre à jour le mot de passe si un nouveau mot de passe est fourni
                if (!empty($new_password)) {
                    $update_data['password'] = password_hash($new_password, PASSWORD_BCRYPT);
                }

                // Mettre à jour le profil
                $this->userModel->updateUser($_SESSION['user_id'], $update_data);

                // Rediriger vers la page de profil avec un message de succès
                $_SESSION['success_message'] = "Profil mis à jour avec succès !";
                header('Location: /todo-app/public/index.php?action=profile');
                exit;
            } else {
                // Mot de passe actuel incorrect
                $_SESSION['error_message'] = "Mot de passe actuel incorrect.";
                header('Location: /todo-app/public/index.php?action=edit-profile');
                exit;
            }
        }

        // Rediriger vers la page d'édition du profil en cas d'erreur
        $_SESSION['error_message'] = "Une erreur s'est produite.";
        header('Location: /todo-app/public/index.php?action=edit-profile');
        exit;
    }
}
?>