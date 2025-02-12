<?php
session_start();
require_once 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $class_id = $_POST['class_id'];
    $subject_id = $_POST['subject_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $date = $_POST['date'];
    $school_id = $_SESSION['school_id'];

    $stmt = $conn->prepare("INSERT INTO examination (name, class_id, subject_id, start_time, end_time, date, school_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $class_id, $subject_id, $start_time, $end_time, $date, $school_id]);

    $_SESSION['message'] = "Examination added successfully!";
    header("Location: exam_list");
    exit();
}
?>