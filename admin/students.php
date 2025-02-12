<?php
session_start();

// Redirect if not logged in or not a school admin
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index");
    exit();
}

if ($_SESSION['role'] != 'school_admin') {
    header("Location: super_admin_dashboard");
    exit();
}

require_once 'db_conn.php';

// Fetch classes for the school// Fetch students for the school with search functionality
$school_id = $_SESSION['school_id'];
$first_name = isset($_GET['first_name']) ? $_GET['first_name'] : '';
$last_name = isset($_GET['last_name']) ? $_GET['last_name'] : '';

$sql = "SELECT students.*, class_table.class_name 
        FROM students 
        JOIN class_table ON students.class_id = class_table.id 
        WHERE students.school_id = :school_id";

if (!empty($first_name)) {
    $sql .= " AND students.fname LIKE :first_name";
}
if (!empty($last_name)) {
    $sql .= " AND students.lname LIKE :last_name";
}

$stmt = $conn->prepare($sql);
$stmt->bindParam(':school_id', $school_id);

if (!empty($first_name)) {
    $first_name_param = "%" . $first_name . "%";
    $stmt->bindParam(':first_name', $first_name_param);
}
if (!empty($last_name)) {
    $last_name_param = "%" . $last_name . "%";
    $stmt->bindParam(':last_name', $last_name_param);
}

$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        <!-- Header -->
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

        <!-- Page Wrapper -->
        <div class="page-wrapper">
            <div class="content container-fluid">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-sub-header">
                                <h3 class="page-title">Students</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="students.html">Admins</a></li>
                                    <li class="breadcrumb-item active">All Students</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Search Form -->
               <!-- Student Search Form -->
<div class="student-group-form">
    <form method="GET" action="">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <input type="text" name="first_name" class="form-control" placeholder="Search by First Name ..." />
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="form-group">
                    <input type="text" name="last_name" class="form-control" placeholder="Search by Second Name ..." />
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


                <!-- Student Table -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-table comman-shadow">
                            <div class="card-body">
                                <div class="page-header">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h3 class="page-title">Students</h3>
                                        </div>
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#add-student"
                                                class="btn btn-primary">
                                                <i class="fas fa-plus"></i>
                                            </a>
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
                                                <th>Class</th>
                                                <th>Gender</th>
                                                <th>Date of Birth</th>
                                                <th>Religion</th>
                                                <th>Parent Contact</th>
                                                <th>Status</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($students as $index => $student): ?>
                                            <tr>
                                                <td><?php echo $index + 1; ?></td>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <a href="#" class="avatar avatar-sm me-2">
                                                            <img class="avatar-img rounded-circle"
                                                                src="<?php echo $student['image'] ?? '../assets/img/profiles/avatar-01.jpg'; ?>"
                                                                alt="User Image" />
                                                        </a>
                                                        <a
                                                            href="#"><?php echo $student['fname'] . ' ' . $student['lname']; ?></a>
                                                    </h2>
                                                </td>
                                                <td><?php echo $student['class_name']; ?></td>
                                                <td><?php echo $student['gender']; ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($student['date_of_birth'])); ?>
                                                </td>
                                                <td><?php echo $student['religion']; ?></td>
                                                <td><?php echo $student['parent_contact']; ?></td>
                                                <td><?php echo $student['status']; ?></td>
                                                <td class="text-end">
                                                    <div class="actions">
                                                        <a href="#" class="btn btn-sm bg-danger-light edit-student"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#edit-student<?php echo $student['id']; ?>">
                                                            <i class="feather-edit"></i>
                                                        </a>
                                                        <form action="delete_student.php" method="POST"
                                                            style="display: inline;">
                                                            <input type="hidden" name="student_id"
                                                                value="<?php echo $student['id']; ?>">
                                                            <button type="submit" class="btn btn-sm bg-danger-light"
                                                                onclick="return confirm('Are you sure you want to delete this student?');">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- Edit Student Modal -->
<div class="modal fade" id="edit-student<?php echo $student['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Edit Student</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="update_student.php" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="form-title student-info">Student Information</h5>
                        </div>
                        <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label>First Name <span class="login-danger">*</span></label>
                                <input class="form-control" type="text" name="first_name" value="<?php echo $student['fname']; ?>" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label>Last Name <span class="login-danger">*</span></label>
                                <input class="form-control" type="text" name="last_name" value="<?php echo $student['lname']; ?>" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label>Gender <span class="login-danger">*</span></label>
                                <select class="form-control" name="gender" required>
                                    <option value="Male" <?php echo $student['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo $student['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label>Class <span class="login-danger">*</span></label>
                                <select class="form-control" name="class_id" required>
                                    <?php foreach ($classes as $class): ?>
                                    <option value="<?php echo $class['id']; ?>" <?php echo $student['class_id'] === $class['id'] ? 'selected' : ''; ?>><?php echo $class['class_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label>Date of Birth <span class="login-danger">*</span></label>
                                <input class="form-control" type="date" name="date_of_birth" value="<?php echo $student['date_of_birth']; ?>" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label>Religion <span class="login-danger">*</span></label>
                                <select class="form-control" name="religion" required>
                                    <option value="Catholic" <?php echo $student['religion'] === 'Catholic' ? 'selected' : ''; ?>>Catholic</option>
                                    <option value="Anglican" <?php echo $student['religion'] === 'Anglican' ? 'selected' : ''; ?>>Anglican</option>
                                    <option value="Pentecost" <?php echo $student['religion'] === 'Pentecost' ? 'selected' : ''; ?>>Pentecost</option>
                                    <option value="Adventist" <?php echo $student['religion'] === 'Adventist' ? 'selected' : ''; ?>>Adventist</option>
                                    <option value="Born Again" <?php echo $student['religion'] === 'Born Again' ? 'selected' : ''; ?>>Born Again</option>
                                    <option value="Islam" <?php echo $student['religion'] === 'Islam' ? 'selected' : ''; ?>>Islam</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label>Parent's Contact <span class="login-danger">*</span></label>
                                <input class="form-control" type="text" name="parent_contact" value="<?php echo $student['parent_contact']; ?>" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label>Status <span class="login-danger">*</span></label>
                                <select class="form-control" name="status" required>
                                    <option value="Active" <?php echo $student['status'] === 'Active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="Inactive" <?php echo $student['status'] === 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group students-up-files">
                                <label>Upload Student Photo</label>
                                <div class="uplod">
                                    <label class="file-upload image-upbtn mb-0">
                                        Choose File <input type="file" name="photo" />
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="student-submit">
                                <button type="submit" class="btn btn-primary">Update</button>
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
            </div>
        </div>

        <!-- Add Student Modal -->

       <!-- Add Student Modal -->
<div class="modal fade" id="add-student" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Add Student</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="add_student.php" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="form-title student-info">Student Information</h5>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label>First Name <span class="login-danger">*</span></label>
                                <input class="form-control" type="text" name="first_name" placeholder="Enter First Name" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label>Last Name <span class="login-danger">*</span></label>
                                <input class="form-control" type="text" name="last_name" placeholder="Enter Last Name" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label>Gender <span class="login-danger">*</span></label>
                                <select class="form-control" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label>Class <span class="login-danger">*</span></label>
                                <select class="form-control" name="class_id" required>
                                    <option value="">Select Class</option>
                                    <?php foreach ($classes as $class): ?>
                                    <option value="<?php echo $class['id']; ?>"><?php echo $class['class_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label>Date of Birth <span class="login-danger">*</span></label>
                                <input class="form-control" type="date" name="date_of_birth" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label>Religion <span class="login-danger">*</span></label>
                                <select class="form-control" name="religion" required>
                                    <option value="">Select Religion</option>
                                    <option value="Catholic">Catholic</option>
                                    <option value="Anglican">Anglican</option>
                                    <option value="Pentecost">Pentecost</option>
                                    <option value="Adventist">Adventist</option>
                                    <option value="Born Again">Born Again</option>
                                    <option value="Islam">Islam</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label>Parent's Contact <span class="login-danger">*</span></label>
                                <input class="form-control" type="text" name="parent_contact" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label>Status <span class="login-danger">*</span></label>
                                <select class="form-control" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group students-up-files">
                                <label>Upload Student Photo</label>
                                <div class="uplod">
                                    <label class="file-upload image-upbtn mb-0">
                                        Choose File <input type="file" name="photo" />
                                    </label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="school_id" value="<?php echo $_SESSION['school_id']; ?>">
                        <div class="col-12">
                            <div class="student-submit">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

        <!-- Edit Student Modal -->



        <!-- Footer -->
        <?php include 'includes/footer.php'; ?>
    </div>

    <!-- JavaScript -->

</body>

</html>