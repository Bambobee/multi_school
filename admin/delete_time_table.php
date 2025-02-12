
<?php
require 'db_conn.php';
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM time_table WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['message'] = "Time table deleted successfully!";
    header("Location: time_table");
}
?>
