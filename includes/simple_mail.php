<?php
function sendSimpleEmail($to, $subject, $body) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: Centre de Formation <noreply@formation-center.ma>' . "\r\n";
    
    return mail($to, $subject, $body, $headers);
}
?>