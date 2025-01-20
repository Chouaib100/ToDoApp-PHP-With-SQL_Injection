<?php
// Démarrez la session pour vérifier si l'utilisateur est connecté
session_start();

// Redirigez l'utilisateur vers la page de connexion s'il n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: /todo-app/public/index.php?action=login');
    exit;
}

// Inclure le modèle Task pour récupérer les tâches
require_once __DIR__ . '/../../models/Task.php';
require_once __DIR__ . '/../../config/database.php';

// Connexion à la base de données
$pdo = require __DIR__ . '/../../config/database.php';

// Récupérer les tâches de l'utilisateur connecté
$taskModel = new Task($pdo);
$tasks = $taskModel->getAll($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="/todo-app/public/assets/css/styles.css">
</head>
<body>
<div class="container">
    <header>
        <h1>Mes Tâches</h1>
        <a href="/todo-app/public/index.php?action=logout" class="logout-button">Déconnexion</a>
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

    <!-- Formulaire pour ajouter une nouvelle tâche -->
    <form action="/todo-app/public/index.php?action=create-task" method="POST" class="task-form">
        <input type="text" name="description" placeholder="Ajouter une nouvelle tâche" required>
        <button type="submit">Ajouter</button>
    </form>

    <!-- Liste des tâches -->
    <ul class="task-list">
        <?php if (empty($tasks)): ?>
            <li>Aucune tâche pour le moment.</li>
        <?php else: ?>
            <?php foreach ($tasks as $task): ?>
                <li class="task-item <?= $task['is_completed'] ? 'completed' : '' ?>">
                    <span><?= htmlspecialchars($task['description']) ?></span>
                    <div class="task-actions">
                        <!-- Formulaire pour marquer une tâche comme terminée -->
                        <form action="/todo-app/public/index.php?action=complete-task" method="POST" style="display: inline;">
                            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                            <button type="submit" class="complete-button">
                                <?= $task['is_completed'] ? 'Marquer comme non terminée' : 'Marquer comme terminée' ?>
                            </button>
                        </form>

                        <!-- Formulaire pour supprimer une tâche -->
                        <form action="/todo-app/public/index.php?action=delete-task" method="POST" style="display: inline;">
                            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                            <button type="submit" class="delete-button">Supprimer</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>
</body>
</html>