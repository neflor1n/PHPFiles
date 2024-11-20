<?php
require 'db.php';

// Получение всех задач из базы данных
$tasks = $db->query("SELECT * FROM tasks ORDER BY deadline ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Task Manager</h1>
    <div id="container">
    <!-- Форма добавления задачи -->
        <form action="add.php" method="POST">
            <input type="text" name="title" placeholder="Название задачи" required>
            <textarea name="description" placeholder="Описание задачи"></textarea>
            <input type="date" name="deadline" required>
            <button type="submit">Добавить задачу</button>
        </form>
    </div>
    <!-- Список задач -->
    <div id="container">
    <table>
        <thead>
            <tr>
                <th>Название</th>
                <th>Описание</th>
                <th>Крайний срок</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?= htmlspecialchars($task['title']) ?></td>
                    <td><?= htmlspecialchars($task['description']) ?></td>
                    <td><?= htmlspecialchars($task['deadline']) ?></td>
                    <td><?= htmlspecialchars($task['status']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $task['id'] ?>">Редактировать</a>
                        <a href="delete.php?id=<?= $task['id'] ?>" onclick="return confirm('Удалить задачу?')">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</body>
</html>
