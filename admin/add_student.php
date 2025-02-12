<?php
session_start();
require_once 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $class_id = intval($_POST['class_id'] ?? 0);
    $date_of_birth = trim($_POST['date_of_birth'] ?? '');
    $religion = trim($_POST['religion'] ?? '');
    $parent_contact = trim($_POST['parent_contact'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $school_id = intval($_POST['school_id'] ?? 0);

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($gender) || empty($class_id) || empty($date_of_birth) || empty($religion) || empty($parent_contact) || empty($status) || empty($school_id)) {
        $_SESSION['error'] = 'All fields are required.';
        header("Location: students");
        exit();
    }

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

    // Insert student data into the database
    $sql = "INSERT INTO students (fname, lname, gender, class_id, date_of_birth, religion, parent_contact, status, image, school_id) 
            VALUES (:first_name, :last_name, :gender, :class_id, :date_of_birth, :religion, :parent_contact, :status, :photo, :school_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':class_id', $class_id);
    $stmt->bindParam(':date_of_birth', $date_of_birth);
    $stmt->bindParam(':religion', $religion);
    $stmt->bindParam(':parent_contact', $parent_contact);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':photo', $photo);
    $stmt->bindParam(':school_id', $school_id);

    // Execute the query
    if ($stmt->execute()) {
        $_SESSION['message'] = 'Student added successfully.';
    } else {
        $_SESSION['error'] = 'Failed to add student.';
    }
    header("Location: students");
    exit();
} else {
    $_SESSION['error'] = 'Invalid request method.';
    header("Location: students");
    exit();
}
?>