<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../phpMailer/src/PHPMailer.php';
require_once __DIR__ . '/../phpMailer/src/SMTP.php';
require_once __DIR__ . '/../phpMailer/src/Exception.php';

function sendRegisterSuccessMail($toEmail, $toName)
{
    $mail = new PHPMailer(true);
    try {
        // SMTP CONFIG
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;


        $mail->Username = 'college.helpdesk.system@gmail.com';
        $mail->Password = 'YOUR_APP_PASSWORD'; // 16-digit app password

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        
        $mail->setFrom('YOUR_GMAIL@gmail.com', 'Helpdesk Website');
        $mail->addAddress($toEmail, $toName);

        
        $mail->isHTML(true);
        $mail->Subject = 'Registration Successful';

        $mail->Body = "
            <h3>Hello $toName,</h3>
            <p>Your registration on the Helpdesk system was successful.</p>
            <p>You can now log in and use the system.</p>
            <br>
            <p>Regards,<br>Helpdesk Team</p>
        ";

        $mail->AltBody = "Hello $toName, Your registration was successful. You can now log in.";

        return $mail->send();

    } catch (Exception $e) {
        error_log("Email Error: {$mail->ErrorInfo}");
        return false;
    }
}