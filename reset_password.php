<?php
session_start();
include 'includes/db.php';
include 'includes/index_header.php';

// Suraksha: Agar bina OTP verify kiye yahan aaye toh wapas bhej do
if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    header("Location: forgot_password.php");
    exit();
}

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    if ($pass !== $confirm_pass) {
        $msg = "<div class='alert alert-danger'>Passwords do not match!</div>";
    } else {
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
        $email = $_SESSION['reset_email'];
        $table = $_SESSION['reset_table'];

        // Update Password and Clear Tokens
        $stmt = $pdo->prepare("UPDATE $table SET password = ?, reset_token = NULL, reset_expires = NULL WHERE email = ?");
        if ($stmt->execute([$hashed_password, $email])) {
            // Success! Clear Session and Redirect
            session_destroy();
            echo "<script>alert('Password updated successfully! Please login.'); window.location='common_login.php';</script>";
            exit();
        } else {
            $msg = "<div class='alert alert-danger'>Database error. Try again.</div>";
        }
    }
}
?>

<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; background: #f4f7f6;">
    <div class="card" style="width: 400px; padding: 2rem; border-radius: 10px;">
        <h3 class="text-center mb-4">New Password</h3>
        <?php echo $msg; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="new_pass" class="form-control" required minlength="6">
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_pass" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Update Password</button>
        </form>
    </div>
</div>