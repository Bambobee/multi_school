<?php
session_start();
include 'db_conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);

    $_SESSION['message'] = "Super Admin deleted successfully!";
    header("Location: super_admin");
    exit();
}
?>