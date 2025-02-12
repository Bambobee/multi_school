<?php
session_start();
include 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $status = $_POST['status'];

    $sql = "INSERT INTO users (name, email, gender, password, status, role) VALUES (:name, :email, :gender, :password, :status, 'super_admin')";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':gender' => $gender,
        ':password' => $password,
        ':status' => $status
    ]);

    $_SESSION['message'] = "Super Admin added successfully!";
    header("Location: super_admin");
    exit();
}
?>