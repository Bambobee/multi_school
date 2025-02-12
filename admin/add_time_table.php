<?php
session_start();
include 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
    // Validate required fields
    if (empty($_POST['teacher_id']) || empty($_POST['class_id']) || empty($_POST['subject_id']) || empty($_POST['date']) || empty($_POST['start_time']) || empty($_POST['end_time'])) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: time_table");
        exit();
    }

    // Sanitize inputs
    $teacher_id = filter_input(INPUT_POST, 'teacher_id', FILTER_SANITIZE_NUMBER_INT);
    $class_id = filter_input(INPUT_POST, 'class_id', FILTER_SANITIZE_NUMBER_INT);
    $subject_id = filter_input(INPUT_POST, 'subject_id', FILTER_SANITIZE_NUMBER_INT);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $start_time = filter_input(INPUT_POST, 'start_time', FILTER_SANITIZE_STRING);
    $end_time = filter_input(INPUT_POST, 'end_time', FILTER_SANITIZE_STRING);
    $school_id = $_SESSION['school_id'];

    try {
        // Insert into the database
        $insertQuery = "INSERT INTO time_table (teacher_id, class_id, subject_id, date, start_time, end_time, school_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->execute([$teacher_id, $class_id, $subject_id, $date, $start_time, $end_time, $school_id]);

        // Set success message
        $_SESSION['message'] = "Time Table added successfully!";
    } catch (PDOException $e) {
        // Handle database errors
        $_SESSION['error'] = "Failed to add time table: " . $e->getMessage();
    }

    header("Location: time_table");
    exit();
} else {
    $_SESSION['error'] = "Invalid request!";
    header("Location: time_table");
    exit();
}
?>