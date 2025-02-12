<?php
session_start();
include 'db_conn.php';

if (!isset($_SESSION['user_id'])){
    header("Location: ../index");
    exit();
}

if ($_SESSION['role'] != 'super_admin') {
    header("Location: school_admin_dashboard");
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
                                <h3 class="page-title">Super Admins</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="students.html">Super Admins</a>
                                    </li>
                                    <li class="breadcrumb-item active">All Super Admins</li>
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
                                            <h3 class="page-title">Super Admins</h3>
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
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Gender</th>
                                                <th>Status</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <?php
                                        $sql = "SELECT * FROM users";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute();
                                                $super_admins = $stmt->fetchAll();
                                                ?>
                                        <tbody>
                                            <?php foreach ($super_admins as $index => $admin): ?>
                                            <tr>
                                                <td><?php echo $index + 1; ?></td>
                                                <td><?php echo htmlspecialchars($admin['name']); ?></td>
                                                <td><?php echo htmlspecialchars($admin['email']); ?></td>
                                                <td><?php echo htmlspecialchars($admin['gender']); ?></td>
                                                <td><?php echo htmlspecialchars($admin['status']); ?></td>
                                                <td class="text-end">
                                                    <div class="actions">
                                                    <a href="delete_super_admin.php?id=<?php echo $admin['id']; ?>" 
                                                        class="btn btn-sm btn-danger bg-success-light me-2" 
                                                        onclick="return confirm('Are you sure you want to delete this Super Admin?');">
                                                        <i class="feather-trash"></i>
                                                        </a>
                                                        <a href="#" data-bs-toggle="modal"
                                                            data-bs-target="#edit-super-admin-<?php echo $admin['id']; ?>"
                                                            class="btn btn-sm bg-danger-light">
                                                            <i class="feather-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                                <!-- Edit Modal -->
                                                <div class="modal fade"
                                                    id="edit-super-admin-<?php echo $admin['id']; ?>" tabindex="-1"
                                                    role="dialog" aria-labelledby="myLargeModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myLargeModalLabel">Edit
                                                                    Super Admin</h4>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="edit_super_admin.php" method="POST">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <h5 class="form-title student-info">Super
                                                                                Admin Information</h5>
                                                                        </div>

                                                                        <!-- Hidden Input for ID -->
                                                                        <input type="hidden" name="id"
                                                                            value="<?php echo $admin['id']; ?>">

                                                                        <!-- Name Field -->
                                                                        <div class="col-12 col-sm-4">
                                                                            <div class="form-group local-forms">
                                                                                <label>Name <span
                                                                                        class="login-danger">*</span></label>
                                                                                <input class="form-control" type="text"
                                                                                    name="name"
                                                                                    value="<?php echo htmlspecialchars($admin['name']); ?>"
                                                                                    placeholder="Enter Name" />
                                                                            </div>
                                                                        </div>

                                                                        <!-- Email Field -->
                                                                        <div class="col-12 col-sm-4">
                                                                            <div class="form-group local-forms">
                                                                                <label>Email <span
                                                                                        class="login-danger">*</span></label>
                                                                                <input class="form-control" type="email"
                                                                                    name="email"
                                                                                    value="<?php echo htmlspecialchars($admin['email']); ?>"
                                                                                    placeholder="Enter Email" />
                                                                            </div>
                                                                        </div>

                                                                        <!-- Gender Field -->
                                                                        <div class="col-12 col-sm-4">
                                                                            <div class="form-group local-forms">
                                                                                <label>Gender <span
                                                                                        class="login-danger">*</span></label>
                                                                                <select name="gender"
                                                                                    class="form-control ">
                                                                                    <option value="female"
                                                                                        <?php echo ($admin['gender'] == 'female') ? 'selected' : ''; ?>>
                                                                                        Female</option>
                                                                                    <option value="male"
                                                                                        <?php echo ($admin['gender'] == 'male') ? 'selected' : ''; ?>>
                                                                                        Male</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Status Field -->
                                                                        <div class="col-12 col-sm-4">
                                                                            <div class="form-group local-forms">
                                                                                <label>Status <span
                                                                                        class="login-danger">*</span></label>
                                                                                <select name="status"
                                                                                    class="form-control ">
                                                                                    <option value="active"
                                                                                        <?php echo ($admin['status'] == 'active') ? 'selected' : ''; ?>>
                                                                                        Active</option>
                                                                                    <option value="inactive"
                                                                                        <?php echo ($admin['status'] == 'inactive') ? 'selected' : ''; ?>>
                                                                                        Inactive</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Submit Button -->
                                                                        <div class="col-12">
                                                                            <div class="student-submit">
                                                                                <button type="submit"
                                                                                    class="btn btn-primary">Submit</button>
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
                <!-- Add Modal -->
                <div class="modal fade" id="add-super-admin" tabindex="-1" role="dialog"
                    aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">Add Super Admin</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="add_super_admin.php" method="POST">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="form-title student-info">Super Admin Information</h5>
                                        </div>

                                        <!-- Name Field -->
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group local-forms">
                                                <label>Name <span class="login-danger">*</span></label>
                                                <input class="form-control" type="text" name="name"
                                                    placeholder="Enter Name" required />
                                            </div>
                                        </div>

                                        <!-- Email Field -->
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group local-forms">
                                                <label>Email <span class="login-danger">*</span></label>
                                                <input class="form-control" type="email" name="email"
                                                    placeholder="Enter Email" required />
                                            </div>
                                        </div>

                                        <!-- Gender Field -->
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group local-forms">
                                                <label>Gender <span class="login-danger">*</span></label>
                                                <select name="gender" class="form-control " required>
                                                    <option value="">Select Gender</option>
                                                    <option value="female">Female</option>
                                                    <option value="male">Male</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Password Field -->
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group local-forms">
                                                <label>Password <span class="login-danger">*</span></label>
                                                <input class="form-control" type="password" name="password"
                                                    placeholder="Enter Password" required />
                                            </div>
                                        </div>

                                        <!-- Status Field -->
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group local-forms">
                                                <label>Status <span class="login-danger">*</span></label>
                                                <select name="status" class="form-control " required>
                                                    <option value="">Select Status</option>
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
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
   
</body>

</html>