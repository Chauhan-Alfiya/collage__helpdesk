<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/SMTP.php';
require_once __DIR__ . '/../PHPMailer/Exception.php';

function sendRegisterSuccessMail($toEmail, $toName)
{
    $mail = new PHPMailer(true);

    try {

        // SMTP SETTINGS
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'college.helpdesk.system@gmail.com';

        // Gmail App Password
        $mail->Password = 'wirl nall wlom eojn';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

        $mail->Port = 465;

        // FROM
        $mail->setFrom(
            'college.helpdesk.system@gmail.com',
            'College Helpdesk'
        );

        // TO
        $mail->addAddress($toEmail, $toName);

        // EMAIL CONTENT
        $mail->isHTML(true);

        $mail->Subject = 'Registration Successful';

        $mail->Body = "
            <div style='font-family:Arial;padding:20px;'>
                <h2 style='color:#0d6efd;'>
                    Welcome $toName
                </h2>

                <p>
                    Your registration was successful.
                </p>

                <p>
                    You can now login into the Helpdesk System.
                </p>

                <br>

                <p>
                    Regards,<br>
                    College Helpdesk Team
                </p>
            </div>
        ";

        $mail->AltBody =
        "Hello $toName, Your registration was successful.";

        return $mail->send();

    } catch (Exception $e) {

        echo "Mailer Error: " . $mail->ErrorInfo;

        return false;
    }
}