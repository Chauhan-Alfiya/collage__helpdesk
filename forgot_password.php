<?php
session_start();
include 'includes/db.php';
include 'includes/index_header.php';

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $tables = ['users', 'student', 'faculty'];
    $user = null;
    $table_found = '';

    // Search user across tables
    foreach ($tables as $table) {
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $table_found = $table;
            break;
        }
    }

    if ($user) {
        // Generate secure token + expiry (1 hour)
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store token in user table
        $stmt = $pdo->prepare("UPDATE $table_found SET reset_token = ?, reset_expires = ? WHERE username = ?");
        $stmt->execute([$token, $expires, $username]);

        // Send email (you can configure SMTP)
        $reset_link = "http://localhost/helpdesk_system/reset_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "Hello $username,\n\nClick the link below to reset your password:\n$reset_link\n\nThis link expires in 1 hour.";
        $headers = "From: no-reply@helpdesk.com\r\n";

        if (mail($user['email'] ?? $user['username'].'@example.com', $subject, $message, $headers)) {
            $msg = "<div class='alert alert-success'>A password reset link has been sent to your email.</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Failed to send email. Please try again later.</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>Username not found.</div>";
    }
}
?>

<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 400px; padding: 2.5rem;">
        <h2 style="text-align:center;">Forgot Password</h2>
        <?php echo $msg; ?>
        <form method="POST">
            <div class="form-group mb-3">
                <label>Enter Your Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
            <p style="text-align:center; margin-top:10px;"><a href="common_login.php">Back to Login</a></p>
        </form>
    </div>
</div>
</body>
</html>
