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

// Fetch classes and subjects for dropdowns
try {
    $classes = $conn->query("SELECT * FROM class_table WHERE school_id = $school_id")->fetchAll(PDO::FETCH_ASSOC);
    $subjects = $conn->query("SELECT * FROM subjects WHERE school_id = $school_id")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    if (!empty($search)) {
        $stmt = $conn->prepare("SELECT * FROM library WHERE school_id = ? AND name LIKE ?");
        $stmt->execute([$school_id, "%$search%"]);
    } else {
        $stmt = $conn->prepare("SELECT * FROM library WHERE school_id = ?");
        $stmt->execute([$school_id]);
    }
    $library = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
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
    <?php include 'includes/header.php'; ?>
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
                                <h3 class="page-title">Library</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="">Admins</a>
                                    </li>
                                    <li class="breadcrumb-item active">All Books</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="student-group-form">
                    <form method="GET" action="">
                        <div class="row">
                            <div class="col-lg-10 col-md-10">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search by Book Name ..."
                                        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
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
                                            <h3 class="page-title">Library</h3>
                                        </div>
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#add-library"
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
                                                <th>Book Name</th>
                                                <th>Class</th>
                                                <th>Author</th>
                                                <th>Subject</th>
                                                <th>Status</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($library as $index => $book) : ?>
                                            <tr>
                                                <td><?= $index + 1; ?></td>
                                                <td><?= htmlspecialchars($book['name']); ?></td>
                                                <td><?= htmlspecialchars($book['class_id']); ?></td>
                                                <td><?= htmlspecialchars($book['subject_id']); ?></td>
                                                <td><?= htmlspecialchars($book['author']); ?></td>
                                                <td><?= htmlspecialchars($book['status']); ?></td>
                                                <td class="text-end">
                                                    <div class="actions">
                                                        <a href="delete_book.php?id=<?= $book['id']; ?>"
                                                            class="btn btn-sm btn-danger bg-success-light me-2"
                                                            onclick="return confirm('Are you sure?');">
                                                            <i class="feather-trash"></i>
                                                        </a>
                                                        <a href="#" data-bs-toggle="modal"
                                                            data-bs-target="#edit-exam-<?= $book['id']; ?>"
                                                            class="btn btn-sm bg-danger-light">
                                                            <i class="feather-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Edit Modal for Each Exam -->
                                            <div class="modal fade" id="edit-exam-<?= $book['id']; ?>" tabindex="-1"
                                                role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Library</h4>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="update_library.php" method="POST">
                                                                <input type="hidden" name="id"
                                                                    value="<?= $book['id']; ?>">
                                                                <div class="form-group">
                                                                    <label>Book Name</label>
                                                                    <input type="text" name="name" class="form-control"
                                                                        value="<?= htmlspecialchars($book['name']); ?>"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Class</label>
                                                                    <select name="class_id" class="form-control"
                                                                        required>
                                                                        <?php foreach ($classes as $class) : ?>
                                                                        <option value="<?= $class['id']; ?>"
                                                                            <?= $class['id'] == $book['class_id'] ? 'selected' : ''; ?>>
                                                                            <?= htmlspecialchars($class['class_name']); ?>
                                                                        </option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Subject</label>
                                                                    <select name="subject_id" class="form-control"
                                                                        required>
                                                                        <?php foreach ($subjects as $subject) : ?>
                                                                        <option value="<?= $subject['id']; ?>"
                                                                            <?= $subject['id'] == $book['subject_id'] ? 'selected' : ''; ?>>
                                                                            <?= htmlspecialchars($subject['subject_name']); ?>
                                                                        </option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Author</label>
                                                                    <input type="text" name="author"
                                                                        class="form-control"
                                                                        value="<?= htmlspecialchars($book['author']); ?>"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Status</label>
                                                                    <select name="status" class="form-control" required>
                                                                        <option value="active"
                                                                            <?= $book['status'] == 'active' ? 'selected' : ''; ?>>
                                                                            Active</option>
                                                                        <option value="inactive"
                                                                            <?= $book['status'] == 'inactive' ? 'selected' : ''; ?>>
                                                                            Inactive</option>
                                                                    </select>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Update</button>
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

                <div class="modal fade" id="add-library" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Add Library</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="add_library.php" method="POST">
                                    <div class="form-group">
                                        <label>Book Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Class</label>
                                        <select name="class_id" class="form-control" required>
                                            <?php foreach ($classes as $class) : ?>
                                            <option value="<?= $class['id']; ?>">
                                                <?= htmlspecialchars($class['class_name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Subject</label>
                                        <select name="subject_id" class="form-control" required>
                                            <?php foreach ($subjects as $subject) : ?>
                                            <option value="<?= $subject['id']; ?>">
                                                <?= htmlspecialchars($subject['subject_name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Author</label>
                                        <input type="text" name="author" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control" required>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Add</button>
                                    </div>
                                </form>
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