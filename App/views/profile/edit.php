<?php
// Démarrez la session pour vérifier si l'utilisateur est connecté
session_start();

// Redirigez l'utilisateur vers la page de connexion s'il n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: /todo-app/public/index.php?action=login');
    exit;
}

// Inclure le modèle User pour récupérer et mettre à jour les informations du profil
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../config/database.php';

// Connexion à la base de données
$pdo = require __DIR__ . '/../../config/database.php';

// Récupérer les informations de l'utilisateur connecté
$userModel = new User($pdo);
$user = $userModel->getUserById($_SESSION['user_id']);

// Traitement du formulaire de mise à jour du profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // Vérifier le mot de passe actuel
    if (password_verify($current_password, $user['password'])) {
        // Mettre à jour les informations de l'utilisateur
        $update_data = ['username' => $username, 'email' => $email];
        if (!empty($new_password)) {
            $update_data['password'] = password_hash($new_password, PASSWORD_BCRYPT);
        }

        $userModel->updateUser($_SESSION['user_id'], $update_data);

        // Rediriger vers le profil avec un message de succès
        $_SESSION['success_message'] = "Profil mis à jour avec succès !";
        header('Location: /todo-app/public/index.php?action=profile');
        exit;
    } else {
        // Mot de passe actuel incorrect
        $_SESSION['error_message'] = "Mot de passe actuel incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer le Profil</title>
    <link rel="stylesheet" href="/todo-app/public/assets/css/styles.css">
</head>
<body>
<div class="container">
    <header>
        <h1>Éditer le Profil</h1>
        <a href="/todo-app/public/index.php?action=dashboard" class="back-button">Retour au tableau de bord</a>
    </header>

    <!-- Affichage des messages de succès ou d'erreur -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success-message">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error-message">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <!-- Formulaire d'édition du profil -->
    <form action="/todo-app/public/index.php?action=edit-profile" method="POST" class="profile-form">
        <div class="form-group">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <div class="form-group">
            <label for="current_password">Mot de passe actuel :</label>
            <input type="password" id="current_password" name="current_password" required>
        </div>

        <div class="form-group">
            <label for="new_password">Nouveau mot de passe (laisser vide pour ne pas changer) :</label>
            <input type="password" id="new_password" name