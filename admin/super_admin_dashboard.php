
<?php
session_start();

if (!isset($_SESSION['user_id'])){
    header("Location: ../index");
    exit();
}

if ($_SESSION['role'] != 'super_admin') {
    header("Location: school_admin_dashboard");
    exit();
}
include 'db_conn.php';
// Fetch total number of schools
$stmt = $conn->prepare("SELECT COUNT(*) as total_schools FROM schools");
$stmt->execute();
$schools = $stmt->fetch(PDO::FETCH_ASSOC)['total_schools'];

// Fetch total number of school admins
$stmt = $conn->prepare("SELECT COUNT(*) as total_admins FROM users WHERE role = 'school_admin'");
$stmt->execute();
$school_admins = $stmt->fetch(PDO::FETCH_ASSOC)['total_admins'];

// Fetch total number of students
$stmt = $conn->prepare("SELECT COUNT(*) as total_students FROM students");
$stmt->execute();
$students = $stmt->fetch(PDO::FETCH_ASSOC)['total_students'];

// Fetch total number of teachers
$stmt = $conn->prepare("SELECT COUNT(*) as total_teachers FROM teachers_table");
$stmt->execute();
$teachers = $stmt->fetch(PDO::FETCH_ASSOC)['total_teachers'];

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
        <div class="header">
            <div class="header-left">
                <a href="index" class="logo">
                    <img src="../assets/img/ministry.png" alt="Logo" />
                </a>
                <a href="index" class="logo logo-small">
                    <img src="../assets/img/ministry.png" alt="Logo" width="30" height="30" />
                </a>
            </div>
            <div class="menu-toggle">
                <a href="javascript:void(0);" id="toggle_btn">
                    <i class="fas fa-bars"></i>
                </a>
            </div>

            <a class="mobile_btn" id="mobile_btn">
                <i class="fas fa-bars"></i>
            </a>

            <ul class="nav user-menu">
                <li class="nav-item zoom-screen me-2">
                    <a href="#" class="nav-link header-nav-list win-maximize">
                        <img src="../assets/img/icons/header-icon-04.svg" alt="" />
                    </a>
                </li>

                <li class="nav-item dropdown has-arrow new-user-menus">
                    <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                        <span class="user-img">
                            <img class="rounded-circle" src="../assets/img/profiles/avatar-01.jpg" width="31"
                                alt="Soeng Souy" />
                            <div class="user-text">
                                <h6>Ministry of education</h6>
                                <p class="text-muted mb-0">Super Admin</p>
                            </div>
                        </span>
                    </a>
                    <div class="dropdown-menu">
                        <div class="user-header">
                            <div class="avatar avatar-sm">
                                <img src="../assets/img/profiles/avatar-01.jpg" alt="User Image"
                                    class="avatar-img rounded-circle" />
                            </div>
                            <div class="user-text">
                                <h6>Ministry of education</h6>
                                <p class="text-muted mb-0">Super Admin</p>
                            </div>
                        </div>
                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#password" href="profile">Change Password</a>
                        <a class="dropdown-item" href="logout">Logout</a>
                    </div>
                </li>
            </ul>
        </div>

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
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-comman w-100">
                            <div class="card-body">
                                <div class="db-widgets d-flex justify-content-between align-items-center">
                                    <div class="db-info">
                                        <h6>School Admins</h6>
                                        <h3><?php echo $school_admins; ?></h3>
                                    </div>
                                    <div class="db-icon">
                                        <img src="../assets/img/icons/dash-icon-02.svg" alt="Dashboard Icon" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-comman w-100">
                            <div class="card-body">
                                <div class="db-widgets d-flex justify-content-between align-items-center">
                                    <div class="db-info">
                                        <h6>Schools</h6>
                                        <h3><?php echo $schools; ?></h3>
                                    </div>
                                    <div class="db-icon">
                                        <img src="../assets/img/icons/dash-icon-03.svg" alt="Dashboard Icon" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-comman w-100">
                            <div class="card-body">
                                <div class="db-widgets d-flex justify-content-between align-items-center">
                                    <div class="db-info">
                                        <h6>Students</h6>
                                        <h3><?php echo $students; ?></h3>
                                    </div>
                                    <div class="db-icon">
                                        <img src="../assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-comman w-100">
                            <div class="card-body">
                                <div class="db-widgets d-flex justify-content-between align-items-center">
                                    <div class="db-info">
                                        <h6>Teachers</h6>
                                        <h3><?php echo $teachers; ?></h3>
                                    </div>
                                    <div class="db-icon">
                                        <img src="../assets/img/icons/dash-icon-04.svg" alt="Dashboard Icon" />
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