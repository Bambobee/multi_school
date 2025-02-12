<?php
session_start();
require_once 'admin/db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $gender = $_POST['gender'];
    $status = 'active';
    $school_id = $_POST['school_id'];
    $role = 'school_admin';

    $sql = "INSERT INTO users (name, email, contact, password, gender, status, school_id, role) 
            VALUES (:name, :email, :contact, :password, :gender, :status, :school_id, :role)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':contact', $contact);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':school_id', $school_id);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'User registered successfully.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to register user.'
        ]);
    }
}
?>