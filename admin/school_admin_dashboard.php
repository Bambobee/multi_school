<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index");
    exit();
}

if ($_SESSION['role'] != 'school_admin') {
    header("Location: super_admin_dashboard");
    exit();
}

// Database connection
include 'db_conn.php'; 


$school_id = $_SESSION['school_id']; 

// Fetch total teachers
$stmt = $conn->prepare("SELECT COUNT(*) AS total_teachers FROM teachers_table WHERE school_id = :school_id");
$stmt->execute(['school_id' => $school_id]);
$total_teachers = $stmt->fetchColumn();

// Fetch total students
$stmt = $conn->prepare("SELECT COUNT(*) AS total_students FROM students WHERE school_id = :school_id");
$stmt->execute(['school_id' => $school_id]);
$total_students = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <title>Jornard School</title>
    <?php 
    include 'includes/links.php'
    ?>
</head>

<body>
    <div class="main-wrapper">
       
    <?php 
            include 'includes/header.php';
            ?>

        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <?php 
            include 'includes/navigation.php';
            ?>
                </div>
            </div>
        </div>

        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-sub-header">
                                <h3 class="page-title">Welcome Admin!</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.html">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active">Dashboard</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-sm-6 col-12 d-flex">
                        <div class="card bg-comman w-100">
                            <div class="card-body">
                                <div class="db-widgets d-flex justify-content-between align-items-center">
                                    <div class="db-info">
                                        <h6>Teachers</h6>
                                        <h3><?php echo $total_teachers; ?></h3>

                                    </div>
                                    <div class="db-icon">
                                        <img src="../assets/img/icons/dash-icon-02.svg" alt="Dashboard Icon" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-xl-6 col-sm-6 col-12 d-flex">
                        <div class="card bg-comman w-100">
                            <div class="card-body">
                                <div class="db-widgets d-flex justify-content-between align-items-center">
                                    <div class="db-info">
                                        <h6>Students</h6>
                                        <h3><?php echo $total_students; ?></h3>
                                    </div>
                                    <div class="db-icon">
                                        <img src="../assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <?php 
   include 'includes/footer.php'
   ?>
</body>

</html>