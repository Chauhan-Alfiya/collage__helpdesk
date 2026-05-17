<?php
session_start();
include 'includes/db.php';

$error = "";

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {

    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm_password']);
    $email    = $_SESSION['reset_email'];

    // VALIDATION
    if (strlen($password) < 6) {

        $error = "Password must be at least 6 characters.";

    } 
    elseif ($password !== $confirm) {

        $error = "Passwords do not match.";

    } 
    else {

        // HASH PASSWORD
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // UPDATE PASSWORD
        $stmt = $pdo->prepare("
            UPDATE users 
            SET password = ?, 
                otp_code = NULL, 
                otp_expires = NULL 
            WHERE email = ?
        ");

        $stmt->execute([$hashed, $email]);

        // REMOVE SESSION
        unset($_SESSION['reset_email']);

        // SUCCESS MESSAGE
        $_SESSION['success_msg'] = "Password reset successfully.";

        // REDIRECT LOGIN PAGE
        header("Location: common_login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <!-- MOBILE RESPONSIVE -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reset Password | Helpdesk</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- BOOTSTRAP ICON -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

</head>

<body class="bg-light">

<div class="container py-4">

    <div class="row justify-content-center align-items-center min-vh-100">

        <div class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4">

            <div class="card border-0 shadow rounded-4 overflow-hidden">

                <!-- TOP -->
                <div class="bg-primary text-white text-center p-4">

                    <i class="bi bi-shield-lock-fill display-5"></i>

                    <h3 class="fw-bold mt-3 mb-1">
                        Reset Password
                    </h3>

                    <p class="small text-white-50 mb-0">
                        Create a strong new password
                    </p>

                </div>

                <!-- BODY -->
                <div class="card-body p-4 p-md-5 bg-white">

                    <?php if ($error): ?>

                        <div class="alert alert-danger border-0 rounded-3 small">

                            <i class="bi bi-exclamation-circle-fill me-2"></i>

                            <?= $error ?>

                        </div>

                    <?php endif; ?>

                    <form method="POST">

                        <!-- NEW PASSWORD -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                New Password
                            </label>

                            <div class="input-group">

                                <span class="input-group-text bg-white">
                                    <i class="bi bi-lock-fill"></i>
                                </span>

                                <input 
                                    type="password"
                                    name="password"
                                    class="form-control py-2"
                                    placeholder="Enter new password"
                                    required
                                >

                            </div>

                        </div>

                        <!-- CONFIRM PASSWORD -->
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Confirm Password
                            </label>

                            <div class="input-group">

                                <span class="input-group-text bg-white">
                                    <i class="bi bi-shield-check"></i>
                                </span>

                                <input 
                                    type="password"
                                    name="confirm_password"
                                    class="form-control py-2"
                                    placeholder="Confirm password"
                                    required
                                >

                            </div>

                        </div>

                        <!-- BUTTON -->
                        <button 
                            type="submit"
                            name="reset_password"
                            class="btn btn-primary w-100 py-2 fw-bold rounded-3"
                        >

                            <i class="bi bi-arrow-repeat me-2"></i>

                            Reset Password

                        </button>

                    </form>

                    <!-- LOGIN -->
                    <div class="text-center mt-4">

                        <a href="common_login.php"
                           class="text-decoration-none fw-semibold small">

                            <i class="bi bi-arrow-left me-1"></i>

                            Back to Login

                        </a>

                    </div>

                </div>

            </div>

            <!-- FOOTER -->
            <div class="text-center mt-3 text-muted small">

                © <?= date('Y') ?> College Helpdesk

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>