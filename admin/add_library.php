<?php
session_start();
require_once 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $class_id = $_POST['class_id'];
    $subject_id = $_POST['subject_id'];
    $author = $_POST['author'];
    $status = $_POST['status'];
    $school_id = $_SESSION['school_id'];

    $stmt = $conn->prepare("INSERT INTO library (name, class_id, subject_id, author, status, school_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $class_id, $subject_id, $author, $status, $school_id]);

    $_SESSION['message'] = "Book added successfully!";
    header("Location: library");
    exit();
}
?>