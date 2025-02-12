<?php
session_start();
require_once 'admin/db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $school_code = $_POST['school_code'];

    $sql = "SELECT id, school_name FROM schools WHERE school_code = :school_code";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':school_code', $school_code);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode([
            'success' => true,
            'school_id' => $row['id'],
            'school_name' => $row['school_name']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'School not found.'
        ]);
    }
}
?>