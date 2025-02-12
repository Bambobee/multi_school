<div class="modal fade" id="password" tabindex="-1" role="dialog" aria-labelledby="addTeacherModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addTeacherModalLabel">Change Password</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="change_password.php">
                    
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="cpassword" class="form-control">
                    </div>
                    <button class="btn btn-primary" type="submit">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
<footer>
    <p>Copyright © 2025 Denzal.</p>
</footer>
</div>
</div>

<script src="../assets/js/jquery-3.6.0.min.js"></script>
<script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/feather.min.js"></script>
<script src="../assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/plugins/toastr/toastr.min.js"></script>
<script src="../assets/plugins/toastr/toastr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Toastr Initialization -->

<!-- Initialize Toastr -->
<script>
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: "toast-top-right",
    timeOut: 5000,
    extendedTimeOut: 1000,
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut"
};

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

<script>
$(document).ready(function() {
    $('.select').select2();
});
</script>