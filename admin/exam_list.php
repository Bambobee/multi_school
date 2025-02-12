
<?php
session_start();
require_once 'db_conn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index");
    exit();
}

if ($_SESSION['role'] != 'school_admin') {
    header("Location: super_admin_dashboard");
    exit();
}

$school_id = $_SESSION['school_id'];

// Fetch examinations for the logged-in school
$stmt = $conn->prepare("SELECT * FROM examination WHERE school_id = ?");
$stmt->execute([$school_id]);
$examinations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch classes and subjects for dropdowns
$classes = $conn->query("SELECT * FROM class_table WHERE school_id = $school_id")->fetchAll();
$subjects = $conn->query("SELECT * FROM subjects WHERE school_id = $school_id")->fetchAll();
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
                                <h3 class="page-title">Examination List</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="students.html">Admins</a>
                                    </li>
                                    <li class="breadcrumb-item active">All Exam List</li>
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
                                            <h3 class="page-title">Examination List</h3>
                                        </div>
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#add-exam" class="btn btn-primary">
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
                                                <th>S.N</th>
                                                <th>Exam Name</th>
                                                <th>class Name</th>
                                                <th>Subject Name</th>
                                                <th>Start time</th>
                                                <th>End time</th>
                                                <th>Date</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($examinations as $index => $exam) : ?>
                                                <tr>
                                                    <td><?= $index + 1; ?></td>
                                                    <td><?= htmlspecialchars($exam['name']); ?></td>
                                                    <td><?= htmlspecialchars($exam['class_id']); ?></td>
                                                    <td><?= htmlspecialchars($exam['subject_id']); ?></td>
                                                    <td><?= htmlspecialchars($exam['start_time']); ?></td>
                                                    <td><?= htmlspecialchars($exam['end_time']); ?></td>
                                                    <td><?= htmlspecialchars($exam['date']); ?></td>
                                                    <td class="text-end">
                                                        <div class="actions">
                                                            <a href="delete_exam.php?id=<?= $exam['id']; ?>" class="btn btn-sm btn-danger bg-success-light me-2" onclick="return confirm('Are you sure?');">
                                                                <i class="feather-trash"></i>
                                                            </a>
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#edit-exam-<?= $exam['id']; ?>" class="btn btn-sm bg-danger-light">
                                                                <i class="feather-edit"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal for Each Exam -->
                                                <div class="modal fade" id="edit-exam-<?= $exam['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Edit Examination</h4>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="update_exam.php" method="POST">
                                                                    <input type="hidden" name="id" value="<?= $exam['id']; ?>">
                                                                    <div class="form-group">
                                                                        <label>Exam Name</label>
                                                                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($exam['name']); ?>" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Class</label>
                                                                        <select name="class_id" class="form-control" required>
                                                                            <?php foreach ($classes as $class) : ?>
                                                                                <option value="<?= $class['id']; ?>" <?= $class['id'] == $exam['class_id'] ? 'selected' : ''; ?>>
                                                                                    <?= htmlspecialchars($class['class_name']); ?>
                                                                                </option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Subject</label>
                                                                        <select name="subject_id" class="form-control" required>
                                                                            <?php foreach ($subjects as $subject) : ?>
                                                                                <option value="<?= $subject['id']; ?>" <?= $subject['id'] == $exam['subject_id'] ? 'selected' : ''; ?>>
                                                                                    <?= htmlspecialchars($subject['subject_name']); ?>
                                                                                </option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Start Time</label>
                                                                        <input type="time" name="start_time" class="form-control" value="<?= htmlspecialchars($exam['start_time']); ?>" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>End Time</label>
                                                                        <input type="time" name="end_time" class="form-control" value="<?= htmlspecialchars($exam['end_time']); ?>" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Date</label>
                                                                        <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($exam['date']); ?>" required>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-primary">Update</button>
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
                <div class="modal fade" id="add-exam" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Add Examination</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="add_exam.php" method="POST">
                                    <div class="form-group">
                                        <label>Exam Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Class</label>
                                        <select name="class_id" class="form-control" required>
                                            <?php foreach ($classes as $class) : ?>
                                                <option value="<?= $class['id']; ?>"><?= htmlspecialchars($class['class_name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Subject</label>
                                        <select name="subject_id" class="form-control" required>
                                            <?php foreach ($subjects as $subject) : ?>
                                                <option value="<?= $subject['id']; ?>"><?= htmlspecialchars($subject['subject_name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Start Time</label>
                                        <input type="time" name="start_time" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>End Time</label>
                                        <input type="time" name="end_time" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input type="date" name="date" class="form-control" required>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
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