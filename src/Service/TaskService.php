<?php
namespace TodoApp\Service;

use TodoApp\Repository\TaskRepositoryInterface;
use TodoApp\Model\Task;

class TaskService {
    private $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository) {
        $this->taskRepository = $taskRepository;
    }

    public function getAllTasks() {
        return $this->taskRepository->findAll();
    }

    public function getTaskById($id) {
        return $this->taskRepository->find($id);
    }

    public function saveTask(Task $task) {
        $this->taskRepository->save($task);
    }

    public function deleteTask($id) {
        $this->taskRepository->delete($id);
    }
}
