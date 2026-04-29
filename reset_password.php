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
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Helpdesk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4">

            <div class="card border-0 shadow-lg rounded-4">

                <div class="card-header bg-success text-white text-center py-4 border-0 rounded-top-4">
                    <i class="bi bi-lock-fill display-4"></i>
                    <h3 class="fw-bold mt-2 mb-0">Reset Password</h3>
                    <p class="small mb-0 text-white-50">Enter new password</p>
                </div>

                <div class="card-body p-4 p-md-5">

                    <?php if ($error): ?>
                        <div class="alert alert-danger border-0 small py-2 mb-4">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success border-0 small py-2 mb-4">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <?= $success ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">NEW PASSWORD</label>
                            <input type="password" name="password"
                                   class="form-control bg-light py-2"
                                   placeholder="Enter new password" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary small">CONFIRM PASSWORD</label>
                            <input type="password" name="confirm_password"
                                   class="form-control bg-light py-2"
                                   placeholder="Confirm password" required>
                        </div>

                        <button type="submit" name="reset_password"
                                class="btn btn-success w-100 py-2 fw-bold shadow-sm">
                            Reset Password
                        </button>

                    </form>

                    <div class="text-center mt-4">
                        <a href="common_login.php" class="text-decoration-none small fw-medium">
                            <i class="bi bi-arrow-left me-1"></i> Back to Login
                        </a>
                    </div>

                </div>
            </div>

            <div class="text-center mt-4 text-muted small">
                <p>&copy; <?= date('Y') ?> College Helpdesk</p>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>




