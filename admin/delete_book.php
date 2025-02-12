<?php
session_start();
require_once 'db_conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM library WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['message'] = "Book deleted successfully!";
    header("Location: library");
    exit();
}
?>
