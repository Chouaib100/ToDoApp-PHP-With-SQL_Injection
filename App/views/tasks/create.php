<?php
// Démarrez la session pour vérifier si l'utilisateur est connecté
session_start();

// Redirigez l'utilisateur vers la page de connexion s'il n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: /todo-app/public/index.php?action=login');
    exit;
}

// Inclure le modèle Task pour créer une tâche
require_once __DIR__ . '/../../models/Task.php';
require_once __DIR__ . '/../../config/database.php';

// Connexion à la base de données
$pdo = require __DIR__ . '/../../config/database.php';

// Traitement du formulaire de création de tâche
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = htmlspecialchars($_POST['description']);
    $user_id = $_SESSION['user_id'];

    // Créer une nouvelle tâche
    $taskModel = new Task($pdo);
    $taskModel->create($user_id, $description);

    // Rediriger vers le tableau de bord avec un message de succès
    $_SESSION['success_message'] = "Tâche créée avec succès !";
    header('Location: /todo-app/public/index.php?action=dashboard');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Tâche</title>
    <link rel="stylesheet" href="/todo-app/public/assets/css/styles.css">
</head>
<body>
<div class="container">
    <header>
        <h1>Créer une Tâche</h1>
        <a href="/todo-app/public/index.php?action=dashboard" class="back-button">Retour au tableau de bord</a>
    </header>

    <!-- Affichage des messages d'erreur -->
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error-message">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <!-- Formulaire de création de tâche -->
    <form action="/todo-app/public/index.php?action=create-task" method="POST" class="task-form">
        <div class="form-group">
            <label for="description">Description de la tâche :</label>
            <textarea id="description" name="description" rows="4" required></textarea>
        </div>

        <button type="submit">Créer la tâche</button>
    </form>
</div>
</body>
</html>