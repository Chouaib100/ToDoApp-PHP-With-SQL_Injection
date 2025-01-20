<?php
// Inclure les contrôleurs
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/TaskController.php';
require_once __DIR__ . '/../app/controllers/ProfileController.php';

// Connexion à la base de données
$pdo = require __DIR__ . '/database.php';

// Initialiser les contrôleurs
$authController = new AuthController($pdo);
$taskController = new TaskController($pdo);
$profileController = new ProfileController($pdo);

// Récupérer l'action demandée (alternative à ?? pour PHP < 7.0)
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

// Router les requêtes en fonction de l'action
switch ($action) {
    // Authentification
    case 'login':
        $authController->login();
        break;
    case 'register':
        $authController->register();
        break;
    case 'logout':
        session_start();
        session_destroy();
        header('Location: /todo-app/public/index.php?action=login');
        exit;
        break;

    // Tâches
    case 'dashboard':
        $taskController->index();
        break;
    case 'create-task':
        $taskController->create();
        break;
    case 'complete-task':
        $taskController->complete();
        break;
    case 'delete-task':
        $taskController->delete();
        break;

    // Profil
    case 'profile':
        $profileController->edit();
        break;
    case 'edit-profile':
        $profileController->edit();
        break;
    case 'update-profile':
        $profileController->update();
        break;

    // Page d'accueil par défaut (redirige vers la connexion)
    default:
        header('Location: /todo-app/public/index.php?action=login');
        exit;
}
?>