<?php

session_start();
include 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $teacher_id = $_POST['teacher_id'];
    $class_id = $_POST['class_id'];
    $subject_id = $_POST['subject_id'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    
    $updateQuery = "UPDATE time_table SET teacher_id = ?, class_id = ?, subject_id = ?, date = ?, start_time = ?, end_time = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->execute([$teacher_id, $class_id, $subject_id, $date, $start_time, $end_time, $id]);


    $_SESSION['message'] = "Time Table Updated successfully!";
    header("Location: time_table");
    exit();
}

?>
