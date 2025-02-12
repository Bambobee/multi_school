<?php
session_start();
require_once 'db_conn.php'; // Include database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index");
    exit();
}

if ($_SESSION['role'] != 'school_admin') {
    header("Location: super_admin_dashboard");
    exit();
}

$school_id = $_SESSION['school_id'];

// Fetch subjects for the logged-in school
$stmt = $conn->prepare("SELECT * FROM subjects WHERE school_id = ?");
$stmt->execute([$school_id]);
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                                <h3 class="page-title">Subjects</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="students.html">Admins</a>
                                    </li>
                                    <li class="breadcrumb-item active">All Subjects</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-table">
                            <div class="card-body">
                                <div class="page-header">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h3 class="page-title">Subjects</h3>
                                        </div>
                                        <div class="col-auto">
                                            <button class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#add-subject">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover datatable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Subject Name</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($subjects as $index => $subject) { ?>
                                            <tr>
                                                <td><?= $index + 1; ?></td>
                                                <td><?= htmlspecialchars($subject['subject_name']); ?></td>
                                                <td class="text-end">


                                                <button type="button" class="btn btn-sm bg-danger-light edit-class" data-bs-toggle="modal" data-bs-target="#editModal<?= $subject['id']; ?>">
                                                            <i class="feather-edit"></i>
                                                        </button>

                                                    <a href="delete_subject.php?id=<?= $subject['id']; ?>"
                                                        class="btn btn-sm btn-danger bg-success-light me-2 delete-class"
                                                        onclick="return confirm('Are you sure?');">
                                                        <i class="feather-trash"></i>
                                                    </a>
                                                </td>
                                              
                                            
                                                
                                                </tr>
                                                   <!-- Edit Modal for Each Subject -->
                                                   <div class="modal fade" id="editModal<?= $subject['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel">Edit Subject</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="edit_subject.php" method="POST">
                                                                    <input type="hidden" name="subject_id" value="<?= $subject['id']; ?>">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Subject Name</label>
                                                                        <input type="text" class="form-control" name="subject_name" value="<?= htmlspecialchars($subject['subject_name']); ?>" required>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Subject Modal -->
                <div class="modal fade" id="add-subject" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <form action="add_subject.php" method="POST">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Subject</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Subject Name</label>
                                        <input type="text" name="subject_name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


                <script>
                document.querySelectorAll(".edit-subject").forEach(button => {
                    button.addEventListener("click", function() {
                        document.getElementById("edit-id").value = this.dataset.id;
                        document.getElementById("edit-name").value = this.dataset.name;
                    });
                });
                </script>

            </div>
        </div>

        <?php include 'includes/footer.php'; ?>
    </div>
</body>

</html>