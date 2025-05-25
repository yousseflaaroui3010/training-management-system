<?php
include 'includes/send_email.php';

// Test email
$result = sendEmail(
    'test@example.com',
    'Test Email',
    '<h1>This is a test</h1><p>If you see this, email works!</p>'
);

if($result) {
    echo "Email sent successfully!";
} else {
    echo "Email failed to send.";
}
?>