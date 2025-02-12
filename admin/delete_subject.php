<?php
require 'db_conn.php';
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM subjects WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['message'] = "Subject deleted successfully!";
    header("Location: subjects");
}
?>
