<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

// Include the database connection file
require_once 'admin/db_conn.php';

// Function to sanitize input
function good_data($data) {
    $result = trim($data);
    $result = stripslashes($result);
    $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
    return $result;
}

// Generate a 6-digit school code
$school_code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $school_name = good_data($_POST['school_name']);
    $school_email = good_data($_POST['school_email']);
    $level = good_data($_POST['level']);
    $location = good_data($_POST['location']);
    $slogun = good_data($_POST['slogun']);

    // File upload handling (optional)
    $upload_dir = "uploads/school_badges/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $badge_path = null; // Initialize $badge_path to null

    if (!empty($_FILES['school_badge']['name'])) {
        $file_name = basename($_FILES['school_badge']['name']);
        $file_tmp = $_FILES['school_badge']['tmp_name'];
        $badge_path = $upload_dir . time() . "_" . $file_name;

        // Validate file type and size
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $file_type = $_FILES['school_badge']['type'];

        if (!in_array($file_type, $allowed_types)) {
            $_SESSION['error'] = "Invalid file type! Only JPG, PNG allowed.";
            header("Location: register");
            exit();
        }

        if ($_FILES['school_badge']['size'] > 2097152) { // 2MB limit
            $_SESSION['error'] = "File size exceeds 2MB!";
            header("Location: register");
            exit();
        }

        if (!move_uploaded_file($file_tmp, $badge_path)) {
            $_SESSION['error'] = "Failed to upload file.";
            header("Location: register");
            exit();
        }
    }

    try {
        // Prepare the SQL statement
        $sql = "INSERT INTO schools (school_name, email, level, location, school_code, slogun, school_badge) 
                VALUES (:school_name, :school_email, :level, :location, :school_code, :slogun, :school_badge)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':school_name', $school_name);
        $stmt->bindParam(':school_email', $school_email);
        $stmt->bindParam(':level', $level);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':school_code', $school_code);
        $stmt->bindParam(':slogun', $slogun);
        $stmt->bindParam(':school_badge', $badge_path);

        if ($stmt->execute()) {
            // Send email with school code
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'kikajjosda@gmail.com'; // Replace with your real email
                $mail->Password = 'gropsajstelpzdzz'; // Use an App Password for security
                $mail->Port = 465;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->setFrom('kikajjosda@gmail.com', 'Ministry of Education');
                $mail->addAddress($school_email);
                $mail->isHTML(true);
                $mail->Subject = "Your School Registration Code";
                $mail->Body = "<p>Your school registration code is: <b>$school_code</b></p>";

                if ($mail->send()) {
                    $_SESSION['message'] = "Registration successful! Check your email for the school code.";
                } else {
                    $_SESSION['error'] = "Registration successful, but email could not be sent.";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Email error: " . $mail->ErrorInfo;
            }
        } else {
            $_SESSION['error'] = "Error: Unable to register the school.";
        }
    } catch (PDOException $ex) {
        $_SESSION['error'] = "Database Error: " . $ex->getMessage();
    }

    // Redirect back to the form
    header("Location: register");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Register</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/feather/feather.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/plugins//toastr/toatr.css">
</head>

<body>
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container p-4">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-2">Register a school</h5>
                                <p class="card-text text-danger">Note: After registration check your school email for the school code which you will use to create an account.</p>
                            </div>
                            <div class="card-body">
                                <form action="register.php" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label>School Name</label>
                                        <input type="text" name="school_name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>School Email</label>
                                        <input type="email" name="school_email" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Level <span class="login-danger">*</span></label>
                                        <select class="form-control select" name="level" required>
                                            <option>Select school level</option>
                                            <option>Pre-primary</option>
                                            <option>Primary</option>
                                            <option>Secondary</option>
                                            <option>Tertiary</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Location</label>
                                        <input type="text" name="location" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>School Badge</label>
                                        <input type="file" name="school_badge" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Slogun</label>
                                        <input type="text" name="slogun" class="form-control" required>
                                    </div>

                                    <div class="row">
                                    <div class="col-6">
                                        <div class="student-submit">
                                            <button type="submit" class="btn btn-primary">
                                                Submit
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="student-submit">
                                            <a href="account" class="btn btn-warning">
                                                Create Account
                                            </a>
                                        </div>
                                    </div>
                                    </div>
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/plugins/toastr/toastr.min.js"></script>
    <script src="assets/plugins/toastr/toastr.js"></script>

    <!-- Toastr Initialization -->
    <script>
    $(document).ready(function() {
        <?php if (isset($_SESSION['message'])): ?>
        toastr.success("<?php echo $_SESSION['message']; ?>");
        <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
        toastr.error("<?php echo $_SESSION['error']; ?>");
        <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    });
    </script>
</body>

</html>