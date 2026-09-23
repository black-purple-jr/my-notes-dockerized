<?php
require "../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->safeLoad();

function send_email(string $to, string $subject, string $htmlBody, string $altBody = ""): bool
{
  $mail = new PHPMailer(true);

  $mail->SMTPDebug = 0;
  $mail->Debugoutput = 'html';

  try {
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = "smtp-relay.brevo.com";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->Username = "b2bc53001@smtp-brevo.com";
    $mail->Password = $_ENV["BREVO_SMTP_KEY"];
    $mail->CharSet = "UTF-8";

    $mail->setFrom("mynotes.support@gmail.com", "My Notes");
    $mail->addAddress($to);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $htmlBody;
    $mail->AltBody = $altBody !== "" ? $altBody : strip_tags($htmlBody);

    $mail->send();
    return true;
  } catch (Exception $e) {
    error_log("Mailer error: " . $mail->ErrorInfo);
    die("Mailer error: " . $mail->ErrorInfo);
  }
}