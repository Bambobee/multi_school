<?php
session_start();
include 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_teacher'])) {
    $teacherId = $_POST['teacher_id'];
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['date_of_birth'];
    $contact = $_POST['contact'];
    $joiningDate = $_POST['joining_date'];
    $qualifications = $_POST['qualifications'];
    $experience = $_POST['experience'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE teachers_table SET fname=?, lname=?, gender=?, date_of_birth=?, contact=?, joining_date=?, qualifications=?, experience=?,  status=? WHERE id=?");
    $stmt->execute([$fname, $lname, $gender, $dob, $contact, $joiningDate, $qualifications, $experience,  $status, $teacherId]);

   
    $_SESSION['message'] = "Teacher updated successfully!";
    header("Location: teachers");
    exit();
}



?>