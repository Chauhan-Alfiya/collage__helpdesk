<?php
session_start();
include 'includes/db.php';

$msg = "";
$email = $_GET['email'] ?? ($_POST['email'] ?? '');

// Form submit check
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_otp'])) {

    $otp   = trim($_POST['otp']);       // User input OTP
    $email = trim($_POST['email']);     // Hidden input email

    // SELECT * from users where email, otp match & expiry > now
    $stmt = $pdo->prepare(
        "SELECT * FROM users 
         WHERE email = ? 
         AND otp_code = ? 
         AND otp_expires > NOW()"
    );
    $stmt->execute([$email, $otp]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // OTP clear kar do
        $pdo->prepare(
            "UPDATE users 
             SET otp_code = NULL, otp_expires = NULL 
             WHERE email = ?"
        )->execute([$email]);

        // Session me email store karo
        $_SESSION['reset_email'] = $email;

        // Redirect to reset password page
        header("Location: reset_password.php");
        exit();
    } else {
        $msg = "<div class='alert alert-danger text-center'>
                    Invalid or expired OTP.
                </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP | Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .otp-box { max-width: 400px; width: 100%; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        #timer { font-weight: bold; color: #dc3545; }
    </style>
</head>
<body>

<div class="otp-box">
    <h2 class="text-center mb-4 text-primary fw-bold">Verify OTP</h2>

    <p class="text-center text-muted small">
        Code sent to: <br><strong><?= htmlspecialchars($email); ?></strong>
    </p>

    <?= $msg; ?>

    <form method="POST">
        <input type="hidden" name="email" value="<?= htmlspecialchars($email); ?>">

        <div class="mb-3">
            <input type="text"
                   name="otp"
                   class="form-control form-control-lg text-center"
                   maxlength="6"
                   placeholder="000000"
                   required
                   autofocus>
        </div>

        <div class="text-center mb-3">
            <small>OTP expires in: <span id="timer">1 minute</span></small>
        </div>

        <button type="submit"
                name="verify_otp"
                class="btn btn-primary w-100 btn-lg">
            Verify & Continue
        </button>

        <div class="text-center mt-3">
            <a href="forgot_password.php"
               class="text-decoration-none small text-muted">
               Resend Code
            </a>
        </div>
    </form>
</div>

</body>
</html>
