<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

function sendMail($to, $subject, $body) {

  try {
    $mail = new PHPMailer(true);

    // SMTP config
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;

    $mail->Username   = 'jofadtechnologies@gmail.com';
    $mail->Password   = 'dknv qcpm epid ycnf';

    // ✅ FIXED SETTINGS
    $mail->SMTPSecure = 'tls'; // changed
    $mail->Port       = 587;   // changed

    // ✅ IMPORTANT FOR RENDER / CLOUD
    $mail->SMTPOptions = [
      'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
      ]
    ];

    // Email setup
    $mail->setFrom('jofadtechnologies@gmail.com', 'TazerH Store');
    $mail->addAddress($to);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $body;

    $mail->send();

    return true;

  } catch (Exception $e) {
    return $e->getMessage(); // ✅ safer
  }
}