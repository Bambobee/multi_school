
<?php
session_start();
require_once 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = $_POST['subject_id'];
    $subject_name = $_POST['subject_name'];

    $sql = "UPDATE class_table SET subject_name = :subject_name WHERE id = :subject_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':subject_name', $subject_name);
    $stmt->bindParam(':subject_id', $subject_id);

    $_SESSION['message'] = "Subject updated successfully!";
    header("Location: subjects");
}
?>
