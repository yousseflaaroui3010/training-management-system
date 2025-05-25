<?php
function sendEmail($to, $subject, $body) {
    // Just log to a file instead of sending
    $log = date('Y-m-d H:i:s') . " - To: $to - Subject: $subject\n";
    file_put_contents('email_log.txt', $log, FILE_APPEND);
    
    // Always return true for testing
    return true;
}
?>