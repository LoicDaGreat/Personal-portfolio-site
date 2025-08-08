<?php
header('Content-Type: text/plain');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'failed';
    exit;
}

// Email configuration
$to = 'nkengloic@gmail.com';  
$subject = 'Freelance Request - Contact Form Submission';


$name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email = isset($_POST['email']) ? trim(strip_tags($_POST['email'])) : '';
$phone = isset($_POST['phone']) ? trim(strip_tags($_POST['phone'])) : '';
$msg = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';

$errors = array();

$suspicious_patterns = array('/\bviagra\b/i', '/\bcialis\b/i', '/\bpharmacy\b/i', '/\bmortgage\b/i');
$content_to_check = $name . ' ' . $email . ' ' . $msg;

foreach ($suspicious_patterns as $pattern) {
    if (preg_match($pattern, $content_to_check)) {
        echo 'failed';
        error_log('Suspicious content detected in contact form');
        exit;
    }
}


$email_subject = $subject;
$email_message = "You have received a new message from your portfolio contact form.\n\n";
$email_message .= "Details:\n";
$email_message .= "--------\n";
$email_message .= "Name: " . $name . "\n";
$email_message .= "Email: " . $email . "\n";
$email_message .= "Phone: " . $phone . "\n";
$email_message .= "Message:\n" . $msg . "\n\n";
$email_message .= "--------\n";
$email_message .= "Sent from: " . $_SERVER['HTTP_HOST'] . "\n";
$email_message .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";
$email_message .= "User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
$email_message .= "Date: " . date('Y-m-d H:i:s') . "\n";

$headers = array();
$headers[] = "MIME-Version: 1.0";
$headers[] = "Content-type: text/plain; charset=utf-8";
$headers[] = "From: Portfolio Contact Form <noreply@" . $_SERVER['HTTP_HOST'] . ">";
$headers[] = "Reply-To: " . $name . " <" . $email . ">";
$headers[] = "Return-Path: noreply@" . $_SERVER['HTTP_HOST'];
$headers[] = "X-Mailer: PHP/" . phpversion();

$headers_string = implode("\r\n", $headers);

$mail_sent = mail($to, $email_subject, $email_message, $headers_string);

if ($mail_sent) {
    echo 'sent';
    
    error_log('Contact form submitted successfully from: ' . $email);
    
    $auto_reply_subject = "Thank you for contacting me!";
    $auto_reply_message = "Hi " . $name . ",\n\n";
    $auto_reply_message .= "Thank you for reaching out! I have received your message and will get back to you as soon as possible.\n\n";
    $auto_reply_message .= "Your message:\n";
    $auto_reply_message .= "\"" . $msg . "\"\n\n";
    $auto_reply_message .= "Best regards,\n";
    $auto_reply_message .= "Your Name"; 
    
    $auto_reply_headers = array();
    $auto_reply_headers[] = "MIME-Version: 1.0";
    $auto_reply_headers[] = "Content-type: text/plain; charset=utf-8";
    $auto_reply_headers[] = "From: Your Name <nkengloic@gmail.com>"; 
    $auto_reply_headers[] = "Return-Path: nkengloic@gmail.com";
    
    $auto_reply_headers_string = implode("\r\n", $auto_reply_headers);
    
    
    mail($email, $auto_reply_subject, $auto_reply_message, $auto_reply_headers_string);
    
} else {
    echo 'failed';
    error_log('Failed to send contact form email to: ' . $to);
}
?>