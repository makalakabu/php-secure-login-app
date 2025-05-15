<?php
session_start();
require '../config.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


//generate 4 random pin
$pin = random_int(1000, 9999);
$_SESSION['pin'] = $pin;



//flag
if (!isset($_SESSION['tries'])) {
    $_SESSION['tries'] = 3;
}
$_SESSION['wrong'] = 3;
//time to expired (1 minute)
$_SESSION['time'] = time() + 60;


//Sending the pin through email
function sendEmail($email, $pin){
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.mail.yahoo.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'Put Your Email Here!';
        $mail->Password = 'Put Your Password Here!';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('Put Your Email Here!', 'Email Authentication');
        $mail->addAddress($email);
        $mail->Subject = 'Your Authentication PIN';
        $mail->Body = "Your PIN is $pin. It expires in 60 sec.";

        $mail->send();
        echo "Email sent.";
    } catch (Exception $e) {
        echo "Error: {$mail->ErrorInfo}";
    }
}

sendEmail($_SESSION['otp_email'], $pin);
header("Location: index.php");
?>
