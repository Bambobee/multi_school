<?php
session_start();
include 'db_conn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index");
    exit();
}

if ($_SESSION['role'] != 'school_admin') {
    header("Location: super_admin_dashboard");
    exit();
}

$school_id = $_SESSION['school_id'];

// Fetch timetable data
$query = "SELECT tt.*, t.fname, t.lname, c.class_name, s.subject_name 
          FROM time_table tt
          JOIN teachers_table t ON tt.teacher_id = t.id
          JOIN class_table c ON tt.class_id = c.id
          JOIN subjects s ON tt.subject_id = s.id
          WHERE tt.school_id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$school_id]);
$timetable = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                                <h3 class="page-title">Time Table</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="students.html">Admins</a>
                                    </li>
                                    <li class="breadcrumb-item active">All Time Table</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-table comman-shadow">
                            <div class="card-body">
                                <div class="page-header">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h3 class="page-title">Time Table</h3>
                                        </div>
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                          
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#add-super-admin" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table
                                        class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                        <thead class="student-thread">
                                            <tr>
                                                <th>S.N</th>
                                                <th>Teacher</th>
                                                <th>class Name</th>
                                                <th>Subject Name</th>
                                                <th>Start time</th>
                                                <th>End time</th>
                                                <th>Date</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                            <tbody>
                                                
                                            <?php foreach ($timetable as $index => $row): ?>
                                            <tr>
                                            <td><?= $index + 1; ?></td>
                                                <td><?= $row['fname'] . ' ' . $row['lname'] ?></td>
                                                <td><?= $row['class_name'] ?></td>
                                                <td><?= $row['subject_name'] ?></td>
                                                <td><?= $row['date'] ?></td>
                                                <td><?= $row['start_time'] ?></td>
                                                <td><?= $row['end_time'] ?></td>
                                                <td class="text-end">
                                                    <div class="actions">
                                                    <a href="delete_time_table.php?id=<?= $row['id']; ?>"
                                                        class="btn btn-sm btn-danger bg-success-light me-2 delete-class"
                                                        onclick="return confirm('Are you sure?');">
                                                        <i class="feather-trash"></i>
                                                    </a>
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#edit-super-admin" class="btn btn-sm bg-danger-light" onclick="populateEditModal(<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') ?>)">
                                                            <i class="feather-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="add-super-admin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">Add Time Table</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="add_time_table.php" method="post">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="form-title student-info">Class Information</h5>
                                        </div>
                                        <div class="col-12 col-sm-12">
                                            <div class="form-group local-forms">
                                                <label>Teacher <span class="login-danger">*</span></label>
                                                <select class="form-control" name="teacher_id" required>
                                                    <?php
                                                    $teachers = $conn->query("SELECT id, fname, lname FROM teachers_table WHERE school_id = $school_id")->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($teachers as $teacher) {
                                                        echo "<option value='{$teacher['id']}'>{$teacher['fname']} {$teacher['lname']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12">
                                            <div class="form-group local-forms">
                                                <label>Class <span class="login-danger">*</span></label>
                                                <select class="form-control" name="class_id" required>
                                                    <?php
                                                    $classes = $conn->query("SELECT id, class_name FROM class_table WHERE school_id = $school_id")->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($classes as $class) {
                                                        echo "<option value='{$class['id']}'>{$class['class_name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12">
                                            <div class="form-group local-forms">
                                                <label>Subject <span class="login-danger">*</span></label>
                                                <select class="form-control" name="subject_id" required>
                                                    <?php
                                                    $subjects = $conn->query("SELECT id, subject_name FROM subjects WHERE school_id = $school_id")->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($subjects as $subject) {
                                                        echo "<option value='{$subject['id']}'>{$subject['subject_name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12">
                                            <div class="form-group local-forms">
                                                <label>Date <span class="login-danger">*</span></label>
                                                <input class="form-control" type="date" name="date" required />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12">
                                            <div class="form-group local-forms">
                                                <label>Start Time <span class="login-danger">*</span></label>
                                                <input class="form-control" type="time" name="start_time" required />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12">
                                            <div class="form-group local-forms">
                                                <label>End Time <span class="login-danger">*</span></label>
                                                <input class="form-control" type="time" name="end_time" required />
                                            </div>
                                        </div>
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

                <!-- Edit Modal -->
                <div class="modal fade" id="edit-super-admin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">Edit Time Table</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="post" action="edit_time_table.php">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="form-title student-info">Class Information</h5>
                                        </div>
                                        <div class="col-12 col-sm-12">
                                            <div class="form-group local-forms">
                                                <label>Teacher <span class="login-danger">*</span></label>
                                                <select class="form-control" name="teacher_id" id="edit_teacher_id" required>
                                                    <?php
                                                    foreach ($teachers as $teacher) {
                                                        echo "<option value='{$teacher['id']}'>{$teacher['fname']} {$teacher['lname']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12">
                                            <div class="form-group local-forms">
                                                <label>Class <span class="login-danger">*</span></label>
                                                <select class="form-control" name="class_id" id="edit_class_id" required>
                                                    <?php
                                                    foreach ($classes as $class) {
                                                        echo "<option value='{$class['id']}'>{$class['class_name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12">
                                            <div class="form-group local-forms">
                                                <label>Subject <span class="login-danger">*</span></label>
                                                <select class="form-control" name="subject_id" id="edit_subject_id" required>
                                                    <?php
                                                    foreach ($subjects as $subject) {
                                                        echo "<option value='{$subject['id']}'>{$subject['subject_name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12">
                                            <div class="form-group local-forms">
                                                <label>Date <span class="login-danger">*</span></label>
                                                <input class="form-control" type="date" name="date" id="edit_date" required />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12">
                                            <div class="form-group local-forms">
                                                <label>Start Time <span class="login-danger">*</span></label>
                                                <input class="form-control" type="time" name="start_time" id="edit_start_time" required />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12">
                                            <div class="form-group local-forms">
                                                <label>End Time <span class="login-danger">*</span></label>
                                                <input class="form-control" type="time" name="end_time" id="edit_end_time" required />
                                            </div>
                                        </div>
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
            </div>


            <?php 
   include 'includes/footer.php'
   ?>
     <script>
        function populateEditModal(data) {
            document.getElementById('edit_teacher_id').value = data.teacher_id;
            document.getElementById('edit_class_id').value = data.class_id;
            document.getElementById('edit_subject_id').value = data.subject_id;
            document.getElementById('edit_date').value = data.date;
            document.getElementById('edit_start_time').value = data.start_time;
            document.getElementById('edit_end_time').value = data.end_time;
        }
    </script>
</body>

</html>