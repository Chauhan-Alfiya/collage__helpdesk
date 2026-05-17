<?php
session_start();
include 'includes/db.php';

$msg = "";
$email = $_GET['email'] ?? ($_POST['email'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_otp'])) {

    $otp   = trim($_POST['otp']);
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("
        SELECT * FROM users
        WHERE email = ?
        AND otp_code = ?
        AND otp_expires > NOW()
    ");

    $stmt->execute([$email, $otp]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        $pdo->prepare("
            UPDATE users
            SET otp_code = NULL,
                otp_expires = NULL
            WHERE email = ?
        ")->execute([$email]);

        $_SESSION['reset_email'] = $email;

        header("Location: reset_password.php");
        exit();

    } else {

        $msg = "
        <div class='alert alert-danger py-2 small rounded-3 text-center'>
            Invalid or Expired OTP
        </div>";
    }
}

$maskedEmail = "";

if (!empty($email)) {

    $parts = explode("@", $email);

    $name = substr($parts[0], 0, 2);

    $maskedEmail = $name . "****@" . $parts[1];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verify OTP</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

</head>

<body class="bg-white">

<div class="container">

    <div class="row justify-content-center align-items-center min-vh-100">

        <div class="col-11 col-sm-8 col-md-5 col-lg-4">

            <!-- OTP CARD -->
            <div class="card border-0 shadow rounded-4">

                <div class="card-body p-4 text-center">

                    <!-- ICON -->
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                         style="width:75px;height:75px;">

                        <i class="bi bi-shield-check text-white fs-2"></i>

                    </div>

                    <!-- TITLE -->
                    <h3 class="fw-bold text-dark mb-2">
                        Verify OTP
                    </h3>

                    <!-- SUBTITLE -->
                    <p class="text-muted small mb-4">

                        Enter the verification code sent to

                        <br>

                       

                    </p>

                    <!-- MESSAGE -->
                    <?= $msg; ?>

                    <!-- FORM -->
                    <form method="POST">

                        <input type="hidden"
                               name="email"
                               value="<?= htmlspecialchars($email); ?>">

                        <!-- OTP BOX -->
                        <div class="mb-3">

                            <input type="text"
                                   name="otp"
                                   maxlength="6"
                                   class="form-control text-center rounded-3 fw-bold py-3"
                                   placeholder="000000"
                                   style="font-size:24px; letter-spacing:10px;"
                                   required>

                        </div>

                        <!-- VERIFY BUTTON -->
                        <button type="submit"
                                name="verify_otp"
                                class="btn btn-primary w-100 rounded-3 fw-semibold py-2">

                            Verify Code

                        </button>

                        <!-- TIMER -->
                        <div class="mt-3">

                            <span class="text-danger small fw-semibold">
                                OTP expires in:
                                <span id="timer">60 sec</span>
                            </span>

                        </div>

                        <!-- RESEND -->
                        <div class="mt-3 small text-muted">

                            Didn't receive code?

                            <a href="forgot_password.php"
                               class="text-decoration-none fw-bold">

                                Resend

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

let timeLeft = 60;

const timer = document.getElementById("timer");

const countdown = setInterval(() => {

    timer.innerHTML = timeLeft + " sec";

    timeLeft--;

    if(timeLeft < 0){

        clearInterval(countdown);

        timer.innerHTML = "Expired";

        document.querySelector("input[name='otp']").disabled = true;

        document.querySelector("button[name='verify_otp']").disabled = true;
    }

},1000);

</script>

</body>
</html>