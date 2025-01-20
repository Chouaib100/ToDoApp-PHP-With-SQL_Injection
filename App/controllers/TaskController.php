<?php
// Inclure le modèle Task pour interagir avec la base de données
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../config/database.php';

class TaskController {
    private $taskModel;

    // Constructeur pour initialiser le modèle Task
    public function __construct($pdo) {
        $this->taskModel = new Task($pdo);
    }

    // Afficher la liste des tâches (tableau de bord)
    public function index() {
        session_start();

        // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: /todo-app/public/index.php?action=login');
            exit;
        }

        // Récupérer les tâches de l'utilisateur connecté
        $tasks = $this->taskModel->getAll($_SESSION['user_id']);

        // Inclure la vue pour afficher les tâches
        require __DIR__ . '/../views/tasks/index.php';
    }

    // Créer une nouvelle tâche
    public function create() {
        session_start();

        // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: /todo-app/public/index.php?action=login');
            exit;
        }

        // Vérifier si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $description = htmlspecialchars($_POST['description']);
            $user_id = $_SESSION['user_id'];

            // Créer la tâche
            $this->taskModel->create($user_id, $description);

            // Rediriger vers le tableau de bord avec un message de succès
            $_SESSION['success_message'] = "Tâche créée avec succès !";
            header('Location: /todo-app/public/index.php?action=dashboard');
            exit;
        }

        // Inclure la vue pour créer une tâche
        require __DIR__ . '/../views/tasks/create.php';
    }

    // Marquer une tâche comme terminée ou non terminée
    public function complete() {
        session_start();

        // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: /todo-app/public/index.php?action=login');
            exit;
        }

        // Vérifier si l'ID de la tâche est fourni
        if (isset($_POST['task_id'])) {
            $task_id = (int)$_POST['task_id'];
            $user_id = $_SESSION['user_id'];

            // Marquer la tâche comme terminée ou non terminée
            $this->taskModel->toggleComplete($task_id, $user_id);

            // Rediriger vers le tableau de bord avec un message de succès
            $_SESSION['success_message'] = "Tâche mise à jour avec succès !";
            header('Location: /todo-app/public/index.php?action=dashboard');
            exit;
        }

        // Rediriger vers le tableau de bord en cas d'erreur
        $_SESSION['error_message'] = "Une erreur s'est produite.";
        header('Location: /todo-app/public/index.php?action=dashboard');
        exit;
    }

    // Supprimer une tâche
    public function delete() {
        session_start();

        // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: /todo-app/public/index.php?action=login');
            exit;
        }

        // Vérifier si l'ID de la tâche est fourni
        if (isset($_POST['task_id'])) {
            $task_id = (int)$_POST['task_id'];
            $user_id = $_SESSION['user_id'];

            // Supprimer la tâche
            $this->taskModel->delete($task_id, $user_id);

            // Rediriger vers le tableau de bord avec un message de succès
            $_SESSION['success_message'] = "Tâche supprimée avec succès !";
            header('Location: /todo-app/public/index.php?action=dashboard');
            exit;
        }

        // Rediriger vers le tableau de bord en cas d'erreur
        $_SESSION['error_message'] = "Une erreur s'est produite.";
        header('Location: /todo-app/public/index.php?action=dashboard');
        exit;
    }
}
?>