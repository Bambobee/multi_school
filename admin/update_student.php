<?php
session_start();
require_once 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = intval($_POST['student_id']);
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $class_id = intval($_POST['class_id'] ?? 0);
    $date_of_birth = trim($_POST['date_of_birth'] ?? '');
    $religion = trim($_POST['religion'] ?? '');
    $parent_contact = trim($_POST['parent_contact'] ?? '');
    $status = trim($_POST['status'] ?? '');

    // Handle file upload
    $photo = null;
    if (!empty($_FILES['photo']['name'])) {
        $upload_dir = "uploads/students/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Validate file type and size
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['photo']['type'];
        $file_size = $_FILES['photo']['size'];

        if (!in_array($file_type, $allowed_types)) {
            $_SESSION['error'] = 'Invalid file type. Only JPEG, PNG, and GIF are allowed.';
            header("Location: students.php");
            exit();
        }

        if ($file_size > 5 * 1024 * 1024) { // 5MB limit
            $_SESSION['error'] = 'File size exceeds the maximum limit of 5MB.';
            header("Location: students.php");
            exit();
        }

        // Generate a unique file name
        $file_name = time() . "_" . basename($_FILES['photo']['name']);
        $photo = $upload_dir . $file_name;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photo)) {
            $_SESSION['error'] = 'Failed to upload file.';
            header("Location: students.php");
            exit();
        }
    }

    // Update student data in the database
    $sql = "UPDATE students SET fname = :first_name, lname = :last_name, gender = :gender, class_id = :class_id, date_of_birth = :date_of_birth, religion = :religion, parent_contact = :parent_contact, status = :status";
    if ($photo) {
        $sql .= ", image = :photo";
    }
    $sql .= " WHERE id = :student_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':class_id', $class_id);
    $stmt->bindParam(':date_of_birth', $date_of_birth);
    $stmt->bindParam(':religion', $religion);
    $stmt->bindParam(':parent_contact', $parent_contact);
    $stmt->bindParam(':status', $status);
    if ($photo) {
        $stmt->bindParam(':photo', $photo);
    }
    $stmt->bindParam(':student_id', $student_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Student updated successfully.';
    } else {
        $_SESSION['error'] = 'Failed to update student.';
    }
    header("Location: students");
    exit();
} else {
    $_SESSION['error'] = 'Invalid request method.';
    header("Location: students");
    exit();
}
?>