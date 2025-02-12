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

// Include the database connection
include 'db_conn.php'; 

// Fetch users from the database
$searchName = isset($_POST['search_name']) ? $_POST['search_name'] : '';
$searchSchool = isset($_POST['search_school']) ? $_POST['search_school'] : '';

$query = "SELECT users.*, schools.school_name AS school_name FROM users 
          JOIN schools ON users.school_id = schools.id 
          WHERE users.role = 'school_admin'";
$params = [];

// Add search conditions
if ($searchName) {
    $query .= " AND users.name LIKE :name";
    $params[':name'] = '%' . $searchName . '%';
}

if ($searchSchool) {
    $query .= " AND schools.school_name LIKE :school";
    $params[':school'] = '%' . $searchSchool . '%';
}

$stmt = $conn->prepare($query);
$stmt->execute($params);
$results = $stmt->fetchAll();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $userId = $_POST['user_id'];
    $status = $_POST['status'];

    $updateQuery = "UPDATE users SET status = :status WHERE id = :id";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->execute([':status' => $status, ':id' => $userId]);

    $_SESSION['message'] = "School Admin updated successfully!";
    header("Location: school_admin"); // Redirect to the same page to see changes
    exit();
}
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
                                <h3 class="page-title">School Admins</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="students.html">Super Admins</a>
                                    </li>
                                    <li class="breadcrumb-item active">All School Admins</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="student-group-form">
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <input type="text" name="search_name" class="form-control"
                                        placeholder="Search by Name ..."
                                        value="<?php echo htmlspecialchars($searchName); ?>" />
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input type="text" name="search_school" class="form-control"
                                        placeholder="Search by School ..."
                                        value="<?php echo htmlspecialchars($searchSchool); ?>" />
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
                                            <h3 class="page-title">School Admins</h3>
                                        </div>

                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table
                                        class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                        <thead class="student-thread">
                                            <tr>

                                                <th>S.N</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>School</th>
                                                <th>Gender</th>
                                                <th>Status</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $counter = 1;
                                            foreach ($results as $row) {
                                                echo "<tr>
                                                    <td>{$counter}</td>
                                                    <td>{$row['name']}</td>
                                                    <td>{$row['email']}</td>
                                                    <td>{$row['school_name']}</td> <!-- Changed to use school_name -->
                                                    <td>{$row['gender']}</td>
                                                    <td>{$row['status']}</td>
                                                    <td class='text-end'>
                                                        <button class='btn btn-sm bg-danger-light' data-bs-toggle='modal' data-bs-target='#edit-admin-{$row['id']}'>
                                                            <i class='feather-edit'></i>
                                                        </button>
                                                    </td>
                                                </tr>";

                                                // Modal for editing status
                                                echo "<div class='modal fade' id='edit-admin-{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='myLargeModalLabel' aria-hidden='true'>
                                                    <div class='modal-dialog modal-lg'>
                                                        <div class='modal-content'>
                                                            <div class='modal-header'>
                                                                <h4 class='modal-title' id='myLargeModalLabel'>Edit Admin Status</h4>
                                                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                            </div>
                                                            <div class='modal-body'>
                                                                <form method='POST' action=''>
                                                                    <input type='hidden' name='user_id' value='{$row['id']}' />
                                                                    <div class='form-group'>
                                                                        <label>Status</label>
                                                                        <select class='form-control' name='status'>
                                                                            <option value='Active' ".($row['status'] == 'Active' ? 'selected' : '').">Active</option>
                                                                            <option value='Inactive' ".($row['status'] == 'Inactive' ? 'selected' : '').">Inactive</option>
                                                                        </select>
                                                                    </div>
                                                                    <button type='submit' name='update_status' class='btn btn-primary'>Update</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>";
                                                $counter++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
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