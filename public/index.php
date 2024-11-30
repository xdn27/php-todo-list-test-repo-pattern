<?php
require '../vendor/autoload.php';

use TodoApp\Repository\TaskRepository;
use TodoApp\Service\TaskService;
use TodoApp\Database\Database;
use TodoApp\Model\Task;

$pdo = Database::getPdo();
$taskRepository = new TaskRepository($pdo);
$taskService = new TaskService($taskRepository);
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $task = new Task();
                $task->setTitle($_POST['title']);
                $task->setDescription($_POST['description']);
                $task->setStatus(0);
                $taskService->saveTask($task);
                $message = 'Task added successfully.';
                break;
            case 'edit':
                $task = $taskService->getTaskById($_POST['id']);
                if ($task) {
                    $task->setTitle($_POST['title']);
                    $task->setDescription($_POST['description']);
                    $task->setStatus($_POST['status']);
                    $taskService->saveTask($task);
                    $message = 'Task updated successfully.';
                }
                break;
            case 'delete':
                $taskService->deleteTask($_POST['id']);
                $message = 'Task deleted successfully.';
                break;
        }

        header('Location: index.php');
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>To-Do List</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    </head>

    <body>
        <div class="container" style="padding-top: 50px;">
            <h1>To-Do List</h1>
            <?php if ($message): ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>

            <!-- Add Task Form -->
            <h4>Add Task</h4>
            <form id="form-task" method="post" action="">
                <input type="hidden" name="action" value="add">
                <label><input type="text" name="title" placeholder="Title" required></label>
                <label><textarea name="description" placeholder="Description"></textarea></label>
                <button type="submit">Add Task</button>
            </form>

            <!-- List Tasks -->
            <h4>Tasks</h4>
            <ul>
                 <?php foreach ($taskService->getAllTasks() as $task): ?>
                    <li>
                        <div class="grid">
                            <div><?php echo $task->getTitle(); ?></div>
                            <div><?php echo $task->getStatus() == '1' ? 'Completed' : 'Not Completed'; ?></div>
                            <div style="text-align: right;">
                                <a href="edit.php?id=<?php echo $task->getId(); ?>">Edit</a> | 
                                <form id="delete-task" method="post" style="display:inline;" action="">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $task->getId(); ?>">
                                    <a href="#" onclick="document.getElementById('delete-task').submit();">Delete</a>
                                </form>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </body>
</html>