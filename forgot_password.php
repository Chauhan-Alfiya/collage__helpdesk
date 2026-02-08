<?php
session_start();
include 'includes/db.php';

use PHPMailer\PHPMailer\PHPMailer;

require 'phpMailer/PHPMailer.php';
require 'phpMailer/SMTP.php';
require 'phpMailer/Exception.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_otp'])) {
    $email = trim($_POST['email']);
    $user = null;

    $tables = ['users', 'student', 'faculty']; 
    foreach ($tables as $table) {
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE email = ?");
        $stmt->execute([$email]); 
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $user = $result;
            $_SESSION['reset_table'] = $table;
            break;
        }
    }

    if ($user) {
        $otp_code = rand(100000, 999999);
        $_SESSION['otp_code'] = $otp_code;
        $_SESSION['reset_email'] = $email;

        $stmt = $pdo->prepare("
            UPDATE " . $_SESSION['reset_table'] . " 
            SET otp_code = ?, otp_expires = DATE_ADD(NOW(), INTERVAL 5 MINUTE) 
            WHERE email = ?
        ");
        $stmt->execute([$otp_code, $email]);

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'calfiya1@gmail.com';
        $mail->Password   = 'your_app_password'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('no-reply@yourdomain.com', 'Helpdesk');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Password Reset';
        $mail->Body = "
            <h3>Hello,</h3>
            <p>Your OTP code is: <b>$otp_code</b></p>
            <p>This code expires in 5 minutes.</p>
        ";

        if ($mail->send()) {
            header("Location: otp.php?email=" . urlencode($email));
            exit();
        } else {
            $error = "Failed to send OTP. Please check email configuration.";
        }

    } else {
        $error = "No account found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4">
            
            <div class="card border-0 shadow-lg rounded-4">
                
                <div class="card-header bg-primary text-white text-center py-4 border-0 rounded-top-4">
                    <i class="bi bi-shield-lock-fill display-4"></i>
                    <h3 clas s="fw-bold mt-2 mb-0">Forgot Password</h3>
                    <p class="small mb-0 text-white-50">Enter email to receive OTP</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger border-0 small py-2 mb-4" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-2"></i> <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary small">EMAIL ADDRESS</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" name="email" class="form-control bg-light border-start-0 py-2" 
                                       placeholder="Enter your registered email" required>
                            </div>
                        </div>
                        
                        <button type="submit" name="send_otp" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                            Send OTP Code
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