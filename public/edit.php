<?php
require '../vendor/autoload.php';

use TodoApp\Repository\TaskRepository;
use TodoApp\Service\TaskService;
use TodoApp\Database\Database;

$pdo = Database::getPdo();
$taskRepository = new TaskRepository($pdo);
$taskService = new TaskService($taskRepository);
$id = $_GET['id'];
$task = $taskService->getTaskById($id);

if (!$task) {
    die('Task not found.');
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $task->setTitle($_POST['title']);
    $task->setDescription($_POST['description']);
    $task->setStatus($_POST['status']);
    $taskService->saveTask($task);
    $message = 'Task updated successfully.';
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit Task</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    </head>
    <body>
        <div class="container" style="padding-top: 50px;">
            <h1>Edit Task</h1>
            <?php if ($message): ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>

            <form method="post" action="">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?php echo $task->getId(); ?>">
                <label>Title: <input type="text" name="title" value="<?php echo $task->getTitle(); ?>" required></label><br>
                <label>Description: <textarea name="description"><?php echo $task->getDescription(); ?></textarea></label><br>
                <label>Status: 
                    <select name="status">
                        <option value="0" <?php if ($task->getStatus() == 0) echo 'selected'; ?>>Not Completed</option>
                        <option value="1" <?php if ($task->getStatus() == 1) echo 'selected'; ?>>Completed</option>
                    </select>
                </label><br>
                <button type="submit">Save Changes</button>
            </form>
            <a href="index.php">Back to Task List</a>
        </div>
    </body>
</html>