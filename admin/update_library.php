<?php
session_start();
require_once 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $class_id = $_POST['class_id'];
    $subject_id = $_POST['subject_id'];
    $author = $_POST['author'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE library SET name = ?, class_id = ?, subject_id = ?, author = ?, status = ? WHERE id = ?");
    $stmt->execute([$name, $class_id, $subject_id, $author, $status, $id]);

    $_SESSION['message'] = "book updated successfully!";
    header("Location: library");
    exit();
}
?>