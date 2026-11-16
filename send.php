<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// 1. Verify reCAPTCHA token
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
$secretKey = '6LcCUCMuAAAAAI4ABlz0LAontB74mp0igYRvpHm0';

if (empty($recaptchaResponse)) {
    echo json_encode(['success' => false, 'message' => 'Please complete the reCAPTCHA check.']);
    exit;
}

// Send request to Google to verify token
$verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
$data = [
    'secret'   => $secretKey,
    'response' => $recaptchaResponse,
    'remoteip' => $_SERVER['REMOTE_ADDR']
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    ]
];
$context  = stream_context_create($options);
$result   = file_get_contents($verifyUrl, false, $context);
$resultJson = json_decode($result);

if (!$resultJson || !$resultJson->success) {
    echo json_encode(['success' => false, 'message' => 'reCAPTCHA verification failed. Please try again.']);
    exit;
}

// 2. Capture and sanitize all form fields
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

// 3. Validation
if (empty($name) || !$email || empty($service) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'workpractice1343@gmail.com';
    $mail->Password   = 'omid owyx pywc mzqt'; // <-- Your 16-char Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('workpractice1343@gmail.com', 'Website Contact Form');
    $mail->addAddress('amoin5331@gmail.com', 'Admin');
    $mail->addReplyTo($email, $name);

    $mail->isHTML(false);
    $mail->Subject = 'New Inquiry (' . $service . ') from ' . $name;
    $mail->Body    = "You have received a new verified project inquiry:\n\n"
                   . "----------------------------------------\n"
                   . "Name: $name\n"
                   . "Email: $email\n"
                   . "Phone: $phone\n"
                   . "Service Requested: $service\n"
                   . "----------------------------------------\n"
                   . "Address: $address\n"
                   . "City: $city | State: $state | Zip: $zip\n"
                   . "Wants Newsletter: $newsletter\n"
                   . "----------------------------------------\n\n"
                   . "Message / Project Details:\n$message";

    $mail->send();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
}