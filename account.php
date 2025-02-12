<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Account</title>

    <link rel="shortcut icon" href="assets/img/favicon.png">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700&display=swap"
        rel="stylesheet">
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
                                <h5 class="card-title mb-2">Create An account</h5>
                            </div>
                            <div class="card-body">
                                <form id="registrationForm">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>School code</label>
                                        <input type="search" id="school_code" placeholder="Enter school code"
                                            name="school_code" class="form-control" required>
                                        <button type="button" id="checkSchoolCode"
                                            class="btn btn-primary mt-2">Check</button> <br>
                                        <small id="schoolName" class="form-text text-muted"></small>
                                        <input type="hidden" id="school_id" name="school_id">
                                    </div>
                                    <div class="form-group">
                                        <label>Contact</label>
                                        <input type="text" name="contact" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Gender <span class="login-danger">*</span></label>
                                        <select class="form-control select" name="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                    </div>
                                    <div class="row">
                                    <div class="col-4 ">
                                        <div class="student-submit">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="student-submit">
                                            <a href="index" class="btn btn-success">Login</a>
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

    <script>
    $(document).ready(function() {
        // Check School Code
        $('#checkSchoolCode').click(function() {
            const schoolCode = $('#school_code').val();
            if (!schoolCode) {
                toastr.error("Please enter a school code.");
                return;
            }

            $.ajax({
                url: 'fetch_school.php',
                type: 'POST',
                data: {
                    school_code: schoolCode
                },
                success: function(response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        $('#schoolName').text("School Name: " + data.school_name);
                        $('#school_id').val(data.school_id);
                    } else {
                        toastr.error(data.message);
                        $('#schoolName').text("");
                        $('#school_id').val("");
                    }
                },
                error: function() {
                    toastr.error("An error occurred while fetching school details.");
                }
            });
        });

        // Submit Registration Form
        $('#registrationForm').submit(function(e) {
            e.preventDefault();
            const formData = $(this).serialize();

            $.ajax({
                url: 'register_user.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        toastr.success(data.message);
                        $('#registrationForm')[0].reset();
                        $('#schoolName').text("");
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function() {
                    toastr.error("An error occurred while registering the user.");
                }
            });
        });
    });
    </script>
</body>

</html>