<?php
require 'db.php';

// Получение данных из формы
$title = $_POST['title'];
$description = $_POST['description'];
$deadline = $_POST['deadline'];

// Добавление задачи в базу данных
$stmt = $db->prepare("INSERT INTO tasks (title, description, deadline) VALUES (?, ?, ?)");
$stmt->execute([$title, $description, $deadline]);

// Перенаправление на главную страницу
header('Location: index.php');
exit();
?>
