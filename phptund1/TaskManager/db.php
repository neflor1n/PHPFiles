<?php
try {
    $db = new PDO('sqlite:tasks.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec("
        CREATE TABLE IF NOT EXISTS tasks (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT,
            deadline DATE,
            status TEXT DEFAULT 'new'
        )
    ");
} catch (Exception $e) {
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
    exit();
}
?>
