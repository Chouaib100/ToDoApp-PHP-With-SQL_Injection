<?php
// Démarrez la session pour accéder aux messages d'erreur
session_start();

// Vérifiez s'il y a un message d'erreur à afficher
$error_message = '';
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Supprimez le message après l'affichage
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="/todo-app/public/assets/css/styles.css">
</head>
<body>
<div class="container">
    <header>
        <h1>Inscription</h1>
    </header>

    <!-- Affichage des messages d'erreur -->
    <?php if (!empty($error_message)): ?>
        <div class="error-message">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire d'inscription -->
    <form action="/todo-app/public/index.php?action=register" method="POST">
        <div class="form-group">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>
        </div>

        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirmez le mot de passe :</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <button type="submit">S'inscrire</button>
    </form>

    <p>Déjà inscrit ? <a href="/todo-app/public/index.php?action=login">Connectez-vous ici</a>.</p>
</div>
</body>
</html>