<?php
session_start();
require_once 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $class_id = $_POST['class_id'];
    $subject_id = $_POST['subject_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $date = $_POST['date'];

    $stmt = $conn->prepare("UPDATE examination SET name = ?, class_id = ?, subject_id = ?, start_time = ?, end_time = ?, date = ? WHERE id = ?");
    $stmt->execute([$name, $class_id, $subject_id, $start_time, $end_time, $date, $id]);

    $_SESSION['message'] = "Examination updated successfully!";
    header("Location: exam_list");
    exit();
}
?>