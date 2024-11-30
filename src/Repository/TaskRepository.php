<?php
namespace TodoApp\Repository;

use TodoApp\Model\Task;
use PDO;

class TaskRepository implements TaskRepositoryInterface {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM tasks");
        return $stmt->fetchAll(PDO::FETCH_CLASS, Task::class);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchObject(Task::class);
    }

    public function save(Task $task) {
        if ($task->getId()) {
            $stmt = $this->pdo->prepare("UPDATE tasks SET title = :title, description = :description, status = :status WHERE id = :id");
            $stmt->execute([
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'status' => $task->getStatus()
            ]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO tasks (title, description, status) VALUES (:title, :description, :status)");
            $stmt->execute([
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'status' => $task->getStatus()
            ]);
            $task->setId($this->pdo->lastInsertId());
        }
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
