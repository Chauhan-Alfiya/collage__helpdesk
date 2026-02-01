<?php
session_start();
include 'includes/db.php';
include 'includes/index_header.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_otp'])) {
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT * FROM users  WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT * FROM student  WHERE email = ?");
    $stmt->execute([$email]);   
    if (!$user) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    $stmt = $pdo->prepare("SELECT * FROM faculty  WHERE email = ?");
    $stmt->execute([$email]);
    if (!$user) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
        

    if ($user) {
        $otp_code = rand(100000, 999999);
        $_SESSION['otp_code'] = $otp_code;
        $_SESSION['reset_email'] = $email;

        // Here you would send the OTP to the user's email address.
        // mail($email, "Your OTP Code", "Your OTP code is: $otp_code");

        header("Location: otp.php");
        exit;
    } else {
        $error = "No account found with that email address.";
    }
}
?>

<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
    <div class="card" style="width: 400px; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <h3 class="text-center mb-4" >Forgot Password</h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            
            <button type="submit" name="send_otp" class="btn btn-primary w-100 py-2">Send OTP to Email</button>
            <div class="text-center mt-3">
                <a href="common_login.php" class="text-decoration-none small">Back to Login</a>
            </div>
        </form>
    </div>
</div>