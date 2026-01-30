<?php
session_start();
include 'includes/db.php';
include 'includes/index_header.php';

$msg = "";
$show_form = false;

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $tables = ['users', 'student', 'faculty'];
    $user = null;
    $table_found = '';

    // Find user by token
    foreach ($tables as $table) {
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE reset_token = ? AND reset_expires >= NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $table_found = $table;
            break;
        }
    }

    if ($user) {
        $show_form = true;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

            // Update password & activate account
            $stmt = $pdo->prepare("
                UPDATE $table_found 
                SET password = ?, is_active = 1, is_deleted = 0, last_password_change = NOW(), reset_token = NULL, reset_expires = NULL
                WHERE username = ?
            ");
            $stmt->execute([$new_password, $user['username']]);

            $msg = "<div class='alert alert-success'>Password reset successful! You can now <a href='common_login.php'>login</a>.</div>";
            $show_form = false;
        }

    } else {
        $msg = "<div class='alert alert-danger'>Invalid or expired reset link.</div>";
    }

} else {
    $msg = "<div class='alert alert-danger'>No reset token provided.</div>";
}
?>

<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 400px; padding: 2.5rem;">
        <h2 style="text-align:center;">Reset Password</h2>
        <?php echo $msg; ?>
        <?php if ($show_form): ?>
            <form method="POST">
                <div class="form-group mb-3">
                    <label>New Password</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
