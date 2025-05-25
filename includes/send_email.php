<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        
        // YOUR GMAIL EMAIL HERE
        $mail->Username   = 'youractual@gmail.com'; // ← Your real Gmail address
        
        // THE 16-CHARACTER APP PASSWORD (NOT your Gmail password!)
        $mail->Password   = 'abcd efgh ijkl mnop';  // ← The app password you generated
        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // From and To
        $mail->setFrom('youractual@gmail.com', 'Centre de Formation');
        $mail->addAddress($to);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        // For debugging: echo "Error: {$mail->ErrorInfo}";
        return false;
    }
}
?>