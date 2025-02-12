<?php

session_start();
include 'db_conn.php';

// Handle adding a new teacher
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_teacher'])) {
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['date_of_birth'];
    $contact = $_POST['contact'];
    $joiningDate = $_POST['joining_date'];
    $qualifications = $_POST['qualifications'];
    $experience = $_POST['experience'];
    $status = $_POST['status'];
    $schoolId = $_SESSION['school_id'];

    // Updated the query to match the number of values passed in execute()
    $stmt = $conn->prepare("INSERT INTO teachers_table (fname, lname, gender, date_of_birth, contact, joining_date, qualifications, experience, school_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$fname, $lname, $gender, $dob, $contact, $joiningDate, $qualifications, $experience, $schoolId, $status]);

    $_SESSION['message'] = "Teacher Added successfully!";
    header("Location: teachers");
    exit();
}

?>
