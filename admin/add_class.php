<?php
session_start();
require_once 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_name = $_POST['class_name'];
    $school_id = $_POST['school_id'];

    $sql = "INSERT INTO class_table (class_name, school_id) VALUES (:class_name, :school_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':class_name', $class_name);
    $stmt->bindParam(':school_id', $school_id);

    if ($stmt->execute()) {
        echo json_encode(['message' => true, 'message' => 'Class added successfully.']);
    } else {
        echo json_encode(['message' => false, 'message' => 'Failed to add class.']);
    }
}
?>