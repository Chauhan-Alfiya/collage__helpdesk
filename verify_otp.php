<?php
session_start();
include 'includes/db.php';
include 'includes/index_header.php';

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

$msg = "";
if (isset($_SESSION['temp_otp'])) {
    $msg = "<div class='alert alert-info'>Testing Mode: Your OTP is <b>" . $_SESSION['temp_otp'] . "</b></div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_btn'])) {
    $otp_input = trim($_POST['otp']);
    $email = $_SESSION['reset_email'];
    $table = $_SESSION['reset_table'];

    $stmt = $pdo->prepare("SELECT * FROM $table WHERE email = ? AND reset_token = ? AND reset_expires > NOW()");
    $stmt->execute([$email, $otp_input]);
    
    if ($stmt->fetch()) {
        $_SESSION['otp_verified'] = true;
        unset($_SESSION['temp_otp']); 
        header("Location: reset_password_final.php");
        exit();
    } else {
        $msg = "<div class='alert alert-danger'>Invalid or Expired OTP!</div>";
    }
}
?>

<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; background: #f4f7f6;">
    <div class="card" style="width: 400px; padding: 2rem; border-radius: 10px;">
        <h3 class="text-center mb-4">Verify OTP</h3>
        <?php echo $msg; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Enter 6-Digit OTP</label>
                <input type="text" name="otp" class="form-control" placeholder="123456" required>
            </div>
            <button type="submit" name="verify_btn" class="btn btn-success w-100">Verify OTP</button>
        </form>
    </div>
</div>