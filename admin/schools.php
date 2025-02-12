<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index");
    exit();
}

if ($_SESSION['role'] != 'super_admin') {
    header("Location: school_admin_dashboard");
    exit();
}

// Database connection
include 'db_conn.php'; 

$school_name = isset($_GET['school_name']) ? $_GET['school_name'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';

// Fetching school data with student and teacher counts
$sql = "
    SELECT 
        s.id AS school_id,
        s.school_name AS school_name,
        s.level AS school_level,
        s.location AS school_location,
        s.email AS school_email,
        COUNT(DISTINCT st.id) AS total_students,
        COUNT(DISTINCT t.id) AS total_teachers,
        s.school_badge AS school_badge,
        s.slogun AS slogun,
        s.school_code AS school_code
    FROM 
        schools s
    LEFT JOIN 
        students st ON st.school_id = s.id
    LEFT JOIN 
        teachers_table t ON t.school_id = s.id
    WHERE 
        s.school_name LIKE :school_name AND 
        s.location LIKE :location
    GROUP BY 
        s.id";
$stmt = $conn->prepare($sql);
$stmt->execute([
    'school_name' => '%' . $school_name . '%',
    'location' => '%' . $location . '%'
]);
$schools = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <title>Jornard School</title>
    <?php include 'includes/links.php'; ?>
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
                    <?php include 'includes/navigation.php'; ?>
                </div>
            </div>
        </div>

        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-sub-header">
                                <h3 class="page-title">Schools</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="students.html">Super Admin</a></li>
                                    <li class="breadcrumb-item active">All Schools</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                
                    <div class="student-group-form">
                    <form method="GET" action="">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <input type="text" name="school_name" class="form-control" placeholder="Search by School ..." value="<?php echo htmlspecialchars($school_name); ?>" />
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input type="text" name="location" class="form-control" placeholder="Search by location ..." value="<?php echo htmlspecialchars($location); ?>" />
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="search-student-btn">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
               

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-table comman-shadow">
                            <div class="card-body">
                                <div class="page-header">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h3 class="page-title">Schools</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                        <thead class="student-thread">
                                            <tr>
                                                <th>S.N</th>
                                                <th>School Name</th>
                                                <th>Level</th>
                                                <th>Location</th>
                                                <th>Email</th>
                                                <th>No. Students</th>
                                                <th>No. Teachers</th>
                                                <th>Badge</th>
                                                <th>Slogun</th>
                                                <th>School Code</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($schools as $index => $school): ?>
                                                <tr>
                                                    <td><?php echo $index + 1; ?></td>
                                                    <td><?php echo htmlspecialchars($school['school_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($school['school_level']); ?></td>
                                                    <td><?php echo htmlspecialchars($school['school_location']); ?></td>
                                                    <td><?php echo htmlspecialchars($school['school_email']); ?></td>
                                                    <td><?php echo htmlspecialchars($school['total_students']); ?></td>
                                                    <td><?php echo htmlspecialchars($school['total_teachers']); ?></td>
                                                    <td><img width="50px" src="../<?php echo htmlspecialchars($school['school_badge']); ?>" alt="Badge" /></td>
                                                    <td><?php echo htmlspecialchars($school['slogun']); ?></td>
                                                    <td><?php echo htmlspecialchars($school['school_code']); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include 'includes/footer.php'; ?>
        </div>
    </div>
</body>
</html>
