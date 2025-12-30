
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/../phpMailer/PHPMailer.php';
require_once __DIR__ . '/../phpMailer/SMTP.php';
require_once __DIR__ . '/../phpMailer/Exception.php';



function sendRegisterSuccessMail($toEmail, $toName) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'calfiya1@gmail.com';   
        $mail->Password   = 'mcrabydqfpjxmnkz';      
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('calfiya1@gmail.com', 'Helpdesk Website');
        $mail->addAddress($toEmail, $toName);

        $mail->isHTML(true);
        $mail->Subject = 'Registration Successful';
        $mail->Body    = "Hello $toName,<br><br>Your registration on this website was successful.<br><br>You can now log in.";
        $mail->AltBody = "Hello $toName,\n\nYour registration on this website was successful.\n\nYou can now log in.";

        $mail->send();
        //echo 'Message sent';
    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}
