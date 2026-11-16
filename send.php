<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Require Composer's autoloader
require 'vendor/autoload.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// 1. Capture and sanitize all form fields
$name       = trim(strip_tags($_POST['name'] ?? ''));
$email      = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$phone      = trim(strip_tags($_POST['phone'] ?? 'Not provided'));
$service    = trim(strip_tags($_POST['service'] ?? ''));
$address    = trim(strip_tags($_POST['address'] ?? 'Not provided'));
$city       = trim(strip_tags($_POST['city'] ?? 'Not provided'));
$state      = trim(strip_tags($_POST['state'] ?? 'Not specified'));
$zip        = trim(strip_tags($_POST['zip'] ?? 'Not provided'));
$message    = trim(strip_tags($_POST['message'] ?? ''));
$newsletter = isset($_POST['newsletter']) ? 'Yes' : 'No';

// 2. Enforce required fields validation
if (empty($name)) {
    echo json_encode(['success' => false, 'message' => 'Please provide your full name.']);
    exit;
}
if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Please provide a valid email address.']);
    exit;
}
if (empty($service)) {
    echo json_encode(['success' => false, 'message' => 'Please choose a service.']);
    exit;
}
if (empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Please enter your project details or message.']);
    exit;
}

$mail = new PHPMailer(true);

try {
    // Server settings for SMTP (Gmail)
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'workpractice1343@gmail.com'; // Your Gmail
    $mail->Password   = 'omid owyx pywc mzqt'; // <-- Your 16-char Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('workpractice1343@gmail.com', 'Website Contact Form');
    $mail->addAddress('testwithcrecentech@gmail.com', 'Admin'); // Destination inbox
    $mail->addReplyTo($email, $name);

    // Content
    $mail->isHTML(false);
    $mail->Subject = 'New Inquiry (' . $service . ') from ' . $name;
    $mail->Body    = "You have received a new project inquiry:\n\n"
                   . "----------------------------------------\n"
                   . "Name: $name\n"
                   . "Email: $email\n"
                   . "Phone: $phone\n"
                   . "Service Requested: $service\n"
                   . "----------------------------------------\n"
                   . "Address: $address\n"
                   . "City: $city | State: $state | Zip: $zip\n"
                   . "Wants Newsletter/Updates: $newsletter\n"
                   . "----------------------------------------\n\n"
                   . "Message / Project Details:\n$message";

    $mail->send();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
}