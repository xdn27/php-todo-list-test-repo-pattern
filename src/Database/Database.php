<?php
namespace TodoApp\Database;

use PDO;

class Database {
    private static $pdo;

    public static function getPdo() {
        if (!self::$pdo) {
            self::$pdo = new PDO('sqlite:todo.db');
            self::$pdo->exec("CREATE TABLE IF NOT EXISTS tasks (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                description TEXT,
                status INTEGER DEFAULT 0
            )");
        }
        return self::$pdo;
    }
}
