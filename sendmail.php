<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

function sendMail($to, $subject, $body) {

  try {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;

    $mail->Username   = 'jofadtechnologies@gmail.com';
    $mail->Password   = 'dknv qcpm epid ycnf';

    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->SMTPOptions = [
      'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
      ]
    ];

    $mail->setFrom('jofadtechnologies@gmail.com', 'TazerH Store');
    $mail->addAddress($to);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $body;

    $mail->send();

    return true;

  } catch (Exception $e) {
    return $e->getMessage();
  }
}