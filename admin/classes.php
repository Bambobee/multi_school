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

require_once 'db_conn.php';
$school_id = $_SESSION['school_id'];
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
                                <h3 class="page-title">Classes</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="students.html">Admins</a>
                                    </li>
                                    <li class="breadcrumb-item active">All Classes</li>
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
                                            <h3 class="page-title">classes</h3>
                                        </div>
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#add-super-admin"
                                                class="btn btn-primary"><i class="fas fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table
                                        class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                        <thead class="student-thread">
                                            <tr>
                                                <th>S.N</th>
                                                <th>Class</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <?php
                                        
                                            $school_id = $_SESSION['school_id'];
                                            $sql = "SELECT * FROM class_table WHERE school_id = :school_id";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->bindParam(':school_id', $school_id);
                                            $stmt->execute();
                                            $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        ?>

                                        <tbody>
                                            <?php foreach ($classes as $index => $class): ?>
                                            <tr>
                                                <td><?php echo $index + 1; ?></td>
                                                <td><?php echo $class['class_name']; ?></td>
                                                <td class="text-end">
                                                    <div class="actions">
                                                        <a href="javascript:;"
                                                            class="btn btn-sm btn-danger bg-success-light me-2 delete-class"
                                                            data-id="<?php echo $class['id']; ?>">
                                                            <i class="feather-trash"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-sm bg-danger-light edit-class"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#edit-super-admin<?php echo $class['id']; ?>"
                                                            data-id="<?php echo $class['id']; ?>"
                                                            data-name="<?php echo $class['class_name']; ?>">
                                                            <i class="feather-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                                <div class="modal fade" id="edit-super-admin<?php echo $class['id']; ?>"
                                                    tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myLargeModalLabel">Edit
                                                                    Class</h4>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="editClassForm">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <h5 class="form-title student-info">Class
                                                                                Information</h5>
                                                                        </div>
                                                                        <div class="col-12 col-sm-12">
                                                                            <div class="form-group local-forms">
                                                                                <label>Class Name <span
                                                                                        class="login-danger">*</span></label>
                                                                                <input class="form-control" type="text"
                                                                                    name="class_name" id="editClassName"
                                                                                    required>
                                                                            </div>
                                                                        </div>
                                                                        <input type="hidden" name="class_id"
                                                                            id="editClassId">
                                                                        <div class="col-12">
                                                                            <div class="student-submit">
                                                                                <button type="submit"
                                                                                    class="btn btn-primary">Update</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="add-super-admin" tabindex="-1" role="dialog"
                    aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">Add Class</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="addClassForm">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="form-title student-info">Class Information</h5>
                                        </div>
                                        <div class="col-12 col-sm-12">
                                            <div class="form-group local-forms">
                                                <label>Class Name <span class="login-danger">*</span></label>
                                                <input class="form-control" type="text" name="class_name"
                                                    placeholder="Enter Class Name" required>
                                            </div>
                                        </div>
                                        <input type="hidden" name="school_id"
                                            value="<?php echo $_SESSION['school_id']; ?>">
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
            $(document).ready(function() {
                $('#addClassForm').submit(function(e) {
                    e.preventDefault();
                    const formData = $(this).serialize();

                    $.ajax({
                        url: 'add_class.php',
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            const data = JSON.parse(response);
                            if (data.success) {
                                toastr.success(data.message);
                                $('#add-super-admin').modal('hide');
                                location.reload(); // Refresh the page to show the new class
                            } else {
                                toastr.error(data.message);
                            }
                        },
                        error: function() {
                            toastr.error("An error occurred while adding the class.");
                        }
                    });
                });
            });

            $(document).on('click', '.edit-class', function() {
                const classId = $(this).data('id');
                const className = $(this).data('name');
                $('#editClassId').val(classId);
                $('#editClassName').val(className);
            });

            $('#editClassForm').submit(function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: 'edit_class.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        const data = JSON.parse(response);
                        if (data.success) {
                            toastr.success(data.message);
                            $('#edit-super-admin').modal('hide');
                            location.reload(); // Refresh the page to show the updated class
                        } else {
                            toastr.error(data.message);
                        }
                    },
                    error: function() {
                        toastr.error("An error occurred while updating the class.");
                    }
                });
            });

            $(document).on('click', '.delete-class', function() {
                const classId = $(this).data('id');

                if (confirm("Are you sure you want to delete this class?")) {
                    $.ajax({
                        url: 'delete_class.php',
                        type: 'POST',
                        data: {
                            class_id: classId
                        },
                        success: function(response) {
                            const data = JSON.parse(response);
                            if (data.success) {
                                toastr.success(data.message);
                                location.reload(); // Refresh the page to reflect the deletion
                            } else {
                                toastr.error(data.message);
                            }
                        },
                        error: function() {
                            toastr.error("An error occurred while deleting the class.");
                        }
                    });
                }
            });
            </script>
</body>

</html>