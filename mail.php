<?php

use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

$mail = new PHPMailer(true);

//Configure an SMTP
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'sayan.manna@innoraft.com';
$mail->Password = 'pvch jubm auwo kgzh';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail->Port = 465;
$mail->SMTPAuth = true;
// Sender information
$mail->setFrom('sayan.manna@innoraft.com', 'Sayan');

// Multiple recipient email addresses and names
// Primary recipients
$mail->addAddress('sayanmanna7631@gmail.com', 'Sayan Manna');

$mail->isHTML(false);

$mail->Subject = 'PHPMailer SMTP test';

$mail->Body    = "PHPMailer the awesome Package\nPHPMailer is working fine for sending mail\nThis is a tutorial to guide you on PHPMailer integration";

// Attempt to send the email
if (!$mail->send()) {
  echo 'Email not sent. An error was encountered: ' . $mail->ErrorInfo;
} else {
  echo 'Message has been sent.';
}

$mail->smtpClose();