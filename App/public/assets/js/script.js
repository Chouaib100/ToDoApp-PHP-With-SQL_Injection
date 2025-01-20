
// Fonction pour afficher/masquer les messages après un délai
function showMessage(messageElement, duration = 3000) {
    if (messageElement) {
        messageElement.style.display = 'block';
        setTimeout(() => {
            messageElement.style.display = 'none';
        }, duration);
    }
}

// Gestion des messages de succès et d'erreur
document.addEventListener('DOMContentLoaded', function () {
    const successMessage = document.querySelector('.success-message');
    const errorMessage = document.querySelector('.error-message');

    showMessage(successMessage);
    showMessage(errorMessage);
});

// Validation des formulaires côté client
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            let valid = true;

            // Vérifier tous les champs obligatoires
            form.querySelectorAll('input[required], textarea[required]').forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                    input.classList.add('error');
                } else {
                    input.classList.remove('error');
                }
            });

            // Empêcher la soumission du formulaire si des champs sont invalides
            if (!valid) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires.');
            }
        });
    });
});

// Gestion des boutons de tâche (marquer comme terminée/supprimer)
document.addEventListener('DOMContentLoaded', function () {
    const taskItems = document.querySelectorAll('.task-item');

    taskItems.forEach(task => {
        const completeButton = task.querySelector('.complete-button');
        const deleteButton = task.querySelector('.delete-button');

        if (completeButton) {
            completeButton.addEventListener('click', function (e) {
                e.preventDefault();
                const taskId = task.dataset.taskId;
                markTaskAsComplete(taskId, task);
            });
        }

        if (deleteButton) {
            deleteButton.addEventListener('click', function (e) {
                e.preventDefault();
                const taskId = task.dataset.taskId;
                deleteTask(taskId, task);
            });
        }
    });
});

// Fonction pour marquer une tâche comme terminée
function markTaskAsComplete(taskId, taskElement) {
    fetch(`/todo-app/public/index.php?action=complete-task&task_id=${taskId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                taskElement.classList.toggle('completed');
                const completeButton = taskElement.querySelector('.complete-button');
                if (completeButton) {
                    completeButton.textContent = taskElement.classList.contains('completed')
                        ? 'Marquer comme non terminée'
                        : 'Marquer comme terminée';
                }
            } else {
                alert('Une erreur s\'est produite.');
            }
        })
        .catch(error => console.error('Erreur:', error));
}

// Fonction pour supprimer une tâche
function deleteTask(taskId, taskElement) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')) {
        fetch(`/todo-app/public/index.php?action=delete-task&task_id=${taskId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    taskElement.remove();
                } else {
                    alert('Une erreur s\'est produite.');
                }
            })
            .catch(error => console.error('Erreur:', error));
    }
}