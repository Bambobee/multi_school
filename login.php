<?php
session_start();
include './admin/db_conn.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user from the database
    $sql = "SELECT * FROM users WHERE email = :email && status  = 'active'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Authentication successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['name'];

        // Check if the user is a school admin and set school_id in session
        if ($user['role'] == 'school_admin' && isset($user['school_id'])) {
            $_SESSION['school_id'] = $user['school_id'];
        }

        // Redirect based on role
        if ($user['role'] == 'super_admin') {
            header("Location: admin/super_admin_dashboard");
        } else {
            header("Location: admin/school_admin_dashboard");
        }
        exit();
    } else {
        // Authentication failed
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: index");
        exit();
    }
}
?>

     