<?php
session_start();
require_once 'db_conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM examination WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['message'] = "Examination deleted successfully!";
    header("Location: exam_list");
    exit();
}
?>