<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
?>

<ul>
    <li>
        <a href="super_admin_dashboard"><i class="feather-grid"></i><span>Dashboard</span></a>
    </li>

    <?php if ($role == 'super_admin'): ?>
        <li>
            <a href="super_admin"><i class="fa fa-user"></i><span>Super Admin</span></a>
        </li>
        <li>
            <a href="schools"><i class="fa fa-building"></i><span>Schools</span></a>
        </li>
        <li>
            <a href="school_admin"><i class="fa fa-users"></i><span>School Admins</span></a>
        </li>
    <?php endif; ?>

    <?php if ($role == 'school_admin'): ?>
       
        <li>
            <a href="classes"><i class="fas fa-clipboard"></i><span>Classes</span></a>
        </li>
        <li>
            <a href="students"><i class="fas fa-graduation-cap"></i><span>Students</span></a>
        </li>
        <li>
            <a href="teachers"><i class="fas fa-chalkboard-teacher"></i><span>Teachers</span></a>
        </li>
        <li>
            <a href="subjects"><i class="fas fa-book"></i><span>Subjects</span></a>
        </li>
        <li>
            <a href="exam_list"><i class="fas fa-clipboard-list"></i><span>Exam List</span></a>
        </li>
        <li>
            <a href="time_table"><i class="fas fa-table"></i><span>Time Table</span></a>
        </li>
        <li>
            <a href="library"><i class="fas fa-book"></i><span>Library</span></a>
        </li>
    <?php endif; ?>
</ul>