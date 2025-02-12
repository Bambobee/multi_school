<?php
session_start();
include 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $status = $_POST['status'];

    $sql = "UPDATE users SET name = :name, email = :email, gender = :gender, status = :status WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':gender' => $gender,
        ':status' => $status,
        ':id' => $id
    ]);

    $_SESSION['message'] = "Super Admin updated successfully!";
    header("Location: super_admin");
    exit();
}
?>