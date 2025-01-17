<?php

require __DIR__ . "/vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);
// $mail->SMTPDebug = SMTP::DEBUG_SERVER;

$mail->isSMTP();
$mail->Host = "smtp.gmail.com";
$mail->SMTPAuth = true;
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
$mail->Username = "kongjiale12@gmail.com";
$mail->Password = "giatjdizlwgbmigx";

$mail->isHTML(true);

return $mail;
