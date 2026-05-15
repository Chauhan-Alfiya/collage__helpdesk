<?php
session_start();
include 'includes/db.php';

$error = "";
$success = "";

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {

    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm_password']);
    $email    = $_SESSION['reset_email'];

    if (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } 
    elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } 
    else {

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            UPDATE users 
            SET password = ?, otp_code = NULL, otp_expires = NULL 
            WHERE email = ?
        ");

        $stmt->execute([$hashed, $email]);

        unset($_SESSION['reset_email']);

        $success = "Password reset successfully. You can login now.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <!-- IMPORTANT FOR MOBILE -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reset Password | Helpdesk</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body class="bg-light">

<div class="container py-4">

    <div class="row justify-content-center align-items-center min-vh-100">

        <!-- RESPONSIVE COLUMN -->
        <div class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4">

            <div class="card border-0 shadow rounded-4 overflow-hidden">

                <!-- HEADER -->
                <div class="bg-primary text-white text-center p-4">

                    <div class="mb-3">
                        <i class="bi bi-shield-lock-fill display-5"></i>
                    </div>

                    <h3 class="fw-bold mb-1">
                        Reset Password
                    </h3>

                    <p class="mb-0 small text-white-50">
                        Secure your account with new password
                    </p>

                </div>

                <!-- BODY -->
                <div class="card-body p-4 p-md-5 bg-white">

                    <?php if ($error): ?>
                        <div class="alert alert-danger small border-0 rounded-3">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success small border-0 rounded-3">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <?= $success ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

                        <!-- PASSWORD -->
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

                        <!-- CONFIRM -->
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