<?php
require 'db.php';

$id = $_GET['id'];

// Удаление задачи из базы данных
$stmt = $db->prepare("DELETE FROM tasks WHERE id = ?");
$stmt->execute([$id]);

header('Location: index.php');
exit();
?>
