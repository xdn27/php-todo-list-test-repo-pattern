<?php
namespace TodoApp\Repository;

use TodoApp\Model\Task;

interface TaskRepositoryInterface {
    public function findAll();
    public function find($id);
    public function save(Task $task);
    public function delete($id);
}
