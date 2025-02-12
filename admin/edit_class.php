<?php
session_start();
require_once 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id = $_POST['class_id'];
    $class_name = $_POST['class_name'];

    $sql = "UPDATE class_table SET class_name = :class_name WHERE id = :class_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':class_name', $class_name);
    $stmt->bindParam(':class_id', $class_id);

    if ($stmt->execute()) {
        echo json_encode(['message' => true, 'message' => 'Class updated successfully.']);
    } else {
        echo json_encode(['message' => false, 'message' => 'Failed to update class.']);
    }
}
?>