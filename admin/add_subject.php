<?php
require 'db_conn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject_name = trim($_POST['subject_name']);
    $school_id = $_SESSION['school_id'];

    $stmt = $conn->prepare("INSERT INTO subjects (subject_name, school_id) VALUES (?, ?)");
    $stmt->execute([$subject_name, $school_id]);

    $_SESSION['message'] = "Subject added successfully!";
    header("Location: subjects");
}
?>
