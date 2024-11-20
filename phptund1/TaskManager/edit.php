<?php
require 'db.php';

$id = $_GET['id'];

// Получение данных задачи
$stmt = $db->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->execute([$id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $status = $_POST['status'];

    // Обновление задачи
    $stmt = $db->prepare("UPDATE tasks SET title = ?, description = ?, deadline = ?, status = ? WHERE id = ?");
    $stmt->execute([$title, $description, $deadline, $status, $id]);

    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование задачи</title>
    <link rel="stylesheet" href="editStyle.css">
</head>
<body>
    <h1>Редактировать задачу</h1>
    <div id="container">
        <form method="POST">
            <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required><br><br>
            <textarea name="description"><?= htmlspecialchars($task['description']) ?></textarea><br><br>
            <input type="date" name="deadline" value="<?= htmlspecialchars($task['deadline']) ?>" required><br><br>
            <select name="status">
                <option value="new" <?= $task['status'] === 'new' ? 'selected' : '' ?>>Новая</option>
                <option value="in progress" <?= $task['status'] === 'in progress' ? 'selected' : '' ?>>В процессе</option>
                <option value="completed" <?= $task['status'] === 'completed' ? 'selected' : '' ?>>Завершена</option>
            </select><br><br>
            <button type="submit">Сохранить</button>
        </form>
    </div>
</body>
</html>
