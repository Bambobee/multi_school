<?php
session_start();
require_once 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = intval($_POST['student_id']);

    $sql = "DELETE FROM students WHERE id = :student_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':student_id', $student_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Student deleted successfully.';
    } else {
        $_SESSION['error'] = 'Failed to delete student.';
    }
    header("Location: students");
    exit();
} else {
    $_SESSION['error'] = 'Invalid request method.';
    header("Location: students");
    exit();
}
?>