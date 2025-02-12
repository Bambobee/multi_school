
<?php
require 'db_conn.php';
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM teachers_table WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['message'] = "Teacher deleted successfully!";
    header("Location: teachers");
}
?>
