<?php
session_start();
require 'db_conn.php'; // Ensure this file correctly connects to your database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id']; // Assuming user ID is stored in session
    $new_password = $_POST['password'];
    $confirm_password = $_POST['cpassword'];

    // Validate inputs
    if (empty($new_password) || empty($confirm_password)) {
        $_SESSION['error'] = 'All fields are required!';
        header('Location: school_admin_dashboard'); // Redirect to profile or relevant page
        exit();
    }

    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = 'Passwords do not match!';
        header('Location: school_admin_dashboard');
        exit();
    }

    // Hash the new password securely
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':id', $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Password updated successfully!';
    } else {
        $_SESSION['error'] = 'Failed to update password!';
    }

    header('Location: school_admin_dashboard');
    exit();
} else {
    $_SESSION['error'] = 'Invalid request!';
    header('Location: school_admin_dashboard');
    exit();
}
