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
include 'db_conn.php';
$school_id = $_SESSION['school_id'];

// Fetch teachers for the logged-in school
$schoolId = $_SESSION['school_id'];

// Initialize search variables
$searchFirstName = isset($_POST['search_first_name']) ? $_POST['search_first_name'] : '';
$searchSecondName = isset($_POST['search_second_name']) ? $_POST['search_second_name'] : '';

// Prepare the SQL query with search functionality
$query = "SELECT * FROM teachers_table WHERE school_id = ?";
$params = [$schoolId];

if ($searchFirstName) {
    $query .= " AND fname LIKE ?";
    $params[] = '%' . $searchFirstName . '%';
}

if ($searchSecondName) {
    $query .= " AND lname LIKE ?";
    $params[] = '%' . $searchSecondName . '%';
}

$stmt = $conn->prepare($query);
$stmt->execute($params);
$teachers = $stmt->fetchAll();
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
                                <h3 class="page-title">Teachers</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="">Admin</a>
                                    </li>
                                    <li class="breadcrumb-item active">All Teachers</li>
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
                                    <input type="text" class="form-control" name="search_first_name" placeholder="Search by First Name ..." value="<?php echo htmlspecialchars($searchFirstName); ?>" />
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="search_second_name" placeholder="Search by Second Name ..." value="<?php echo htmlspecialchars($searchSecondName); ?>" />
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
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-table comman-shadow">
                            <div class="card-body">
                                <div class="page-header">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h3 class="page-title">Teachers</h3>
                                        </div>
                                        <div class="col-auto text-end float-end ms-auto download-grp">

                                            <a href="#" data-bs-toggle="modal" data-bs-target="#add-teacher"
                                                class="btn btn-primary"><i class="fas fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table
                                        class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                        <thead class="student-thread">
                                            <tr>

                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Date of Birth</th>
                                                <th>Contact</th>
                                                <th>Joining Date</th>
                                                <th>Qualifications</th>
                                                <th>Experience</th>
                                                <th>Status</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($teachers as $teacher): ?>
                                            <tr>
                                                <td><?php echo $teacher['id']; ?></td>
                                                <td><?php echo $teacher['fname'] . ' ' . $teacher['lname']; ?></td>
                                                <td><?php echo $teacher['gender']; ?></td>
                                                <td><?php echo $teacher['date_of_birth']; ?></td>
                                                <td><?php echo $teacher['contact']; ?></td>
                                                <td><?php echo $teacher['joining_date']; ?></td>
                                                <td><?php echo $teacher['qualifications']; ?></td>
                                                <td><?php echo $teacher['experience']; ?></td>
                                                <td><?php echo $teacher['status']; ?></td>
                                                <td class="text-end">
                                                    <div class="actions">
                                                        <a href="javascript:;" class="btn btn-sm bg-danger-light"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#edit-teacher-<?php echo $teacher['id']; ?>">
                                                            <i class="feather-edit"></i>
                                                        </a>
                                                        <a href="delete_teacher.php?id=<?= $teacher['id']; ?>"
                                                        class="btn btn-sm btn-danger bg-success-light me-2 delete-class"
                                                        onclick="return confirm('Are you sure?');">
                                                        <i class="feather-trash"></i>
                                                    </a>
                                                      
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Edit Teacher Modal -->
                                            <div class="modal fade" id="edit-teacher-<?php echo $teacher['id']; ?>"
                                                tabindex="-1" role="dialog" aria-labelledby="editTeacherModalLabel"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="editTeacherModalLabel">Edit
                                                                Teacher</h4>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="POST" action="edit_teacher.php">
                                                                <input type="hidden" name="teacher_id"
                                                                    value="<?php echo $teacher['id']; ?>" />
                                                                <div class="row">
                                                                    <div class="col-12 col-sm-4">
                                                                        <div class="form-group local-forms">
                                                                            <label>First Name <span
                                                                                    class="login-danger">*</span></label>
                                                                            <input class="form-control" type="text"
                                                                                name="first_name"
                                                                                value="<?php echo $teacher['fname']; ?>"
                                                                                required />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-sm-4">
                                                                        <div class="form-group local-forms">
                                                                            <label>Last Name <span
                                                                                    class="login-danger">*</span></label>
                                                                            <input class="form-control" type="text"
                                                                                name="last_name"
                                                                                value="<?php echo $teacher['lname']; ?>"
                                                                                required />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-sm-4">
                                                                        <div class="form-group local-forms">
                                                                            <label>Gender <span
                                                                                    class="login-danger">*</span></label>
                                                                            <select class="form-control "
                                                                                name="gender" required>
                                                                                <option value="Male"
                                                                                    <?php echo ($teacher['gender'] == 'Male') ? 'selected' : ''; ?>>
                                                                                    Male</option>
                                                                                <option value="Female"
                                                                                    <?php echo ($teacher['gender'] == 'Female') ? 'selected' : ''; ?>>
                                                                                    Female</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-sm-4">
                                                                        <div class="form-group local-forms">
                                                                            <label>Date of Birth <span
                                                                                    class="login-danger">*</span></label>
                                                                            <input class="form-control" type="date"
                                                                                name="date_of_birth"
                                                                                value="<?php echo $teacher['date_of_birth']; ?>"
                                                                                required />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-sm-4">
                                                                        <div class="form-group local-forms">
                                                                            <label>Contact <span
                                                                                    class="login-danger">*</span></label>
                                                                            <input class="form-control" type="text"
                                                                                name="contact"
                                                                                value="<?php echo $teacher['contact']; ?>"
                                                                                required />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-sm-4">
                                                                        <div class="form-group local-forms">
                                                                            <label>Joining Date <span
                                                                                    class="login-danger">*</span></label>
                                                                            <input class="form-control" type="date"
                                                                                name="joining_date"
                                                                                value="<?php echo $teacher['joining_date']; ?>"
                                                                                required />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-sm-4">
                                                                        <div class="form-group local-forms">
                                                                            <label>Qualifications <span
                                                                                    class="login-danger">*</span></label>
                                                                            <input class="form-control" type="text"
                                                                                name="qualifications"
                                                                                value="<?php echo $teacher['qualifications']; ?>"
                                                                                required />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-sm-4">
                                                                        <div class="form-group local-forms">
                                                                            <label>Experience <span
                                                                                    class="login-danger">*</span></label>
                                                                            <input class="form-control" type="text"
                                                                                name="experience"
                                                                                value="<?php echo $teacher['experience']; ?>"
                                                                                required />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-sm-4">
                                                                        <div class="form-group local-forms">
                                                                            <label>Status <span
                                                                                    class="login-danger">*</span></label>
                                                                            <select class="form-control "
                                                                                name="status" required>
                                                                                <option value="Active"
                                                                                    <?php echo ($teacher['status'] == 'Active') ? 'selected' : ''; ?>>
                                                                                    Active</option>
                                                                                <option value="Inactive"
                                                                                    <?php echo ($teacher['status'] == 'Inactive') ? 'selected' : ''; ?>>
                                                                                    Inactive</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="student-submit">
                                                                            <button type="submit" name="update_teacher"
                                                                                class="btn btn-primary">Update</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="add-teacher" tabindex="-1" role="dialog"
                    aria-labelledby="addTeacherModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="addTeacherModalLabel">Add Teacher</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="add_teacher.php">
                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group local-forms">
                                                <label>First Name <span class="login-danger">*</span></label>
                                                <input class="form-control" type="text" name="first_name" required />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group local-forms">
                                                <label>Last Name <span class="login-danger">*</span></label>
                                                <input class="form-control" type="text" name="last_name" required />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group local-forms">
                                                <label>Gender <span class="login-danger">*</span></label>
                                                <select class="form-control " name="gender" required>
                                                    <option>Select Gender</option>
                                                    <option>Female</option>
                                                    <option>Male</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group local-forms">
                                                <label>Date of Birth <span class="login-danger">*</span></label>
                                                <input class="form-control" type="date" name="date_of_birth" required />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group local-forms">
                                                <label>Contact <span class="login-danger">*</span></label>
                                                <input class="form-control" type="text" name="contact" required />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group local-forms">
                                                <label>Joining Date <span class="login-danger">*</span></label>
                                                <input class="form-control" type="date" name="joining_date" required />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group local-forms">
                                                <label>Qualifications <span class="login-danger">*</span></label>
                                                <input class="form-control" type="text" name="qualifications"
                                                    required />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group local-forms">
                                                <label>Experience <span class="login-danger">*</span></label>
                                                <input class="form-control" type="text" name="experience" required />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group local-forms">
                                                <label>Status <span class="login-danger">*</span></label>
                                                <select class="form-control " name="status" required>
                                                    <option>Select Status</option>
                                                    <option>Active</option>
                                                    <option>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="student-submit">
                                                <button type="submit" name="add_teacher"
                                                    class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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