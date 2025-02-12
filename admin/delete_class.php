<?php
session_start();
require_once 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id = $_POST['class_id'];

    $sql = "DELETE FROM class_table WHERE id = :class_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':class_id', $class_id);

    if ($stmt->execute()) {
        echo json_encode(['message' => true, 'message' => 'Class deleted successfully.']);
    } else {
        echo json_encode(['message' => false, 'message' => 'Failed to delete class.']);
    }
}
?>