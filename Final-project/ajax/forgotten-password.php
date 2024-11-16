<?php

require_once '../includes/config.php';
header('Content-Type: application/json');

// Sanitize and validate email input
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email address']);
    exit;
}

try {
    // Connect to the database
    $pdo = connect_db();

    // Check if the email exists
    $stmt = $pdo->prepare("SELECT id, username FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if (!$user) {
        echo json_encode(['status' => 'error', 'message' => 'Email address not found']);
        exit;
    }

    // Generate a random pin and token
    $pin = rand(100000, 999999);
    $token = bin2hex(random_bytes(16));

    // Insert the pin, token, and expiration time into the reset_password table with 1-hour expiry
    $insert = $pdo->prepare("
        INSERT INTO reset_password (user_id, pin, token, created_at, expires_at)
        VALUES (:user_id, :pin, :token, NOW(), DATE_ADD(NOW(), INTERVAL 1 HOUR))
    ");
    $insert->execute([
        ':user_id' => $user['id'],
        ':pin' => $pin,
        ':token' => $token
    ]);

    // Prepare email placeholders and template path
    $subject = "Password Reset Request";
    $template_path = '../includes/forgot-password-email.html';
    $placeholders = [
        'username' => $user['username'],
        'reset_link' => $_ENV['APP_URL_ROOT'] . "set-password.php?pin=$pin&token=$token"
    ];

    // Send email using the send_email function with dynamic template and placeholders
    $mail = send_email($email, $user['username'], $subject, $template_path, $placeholders);

    // Check if the email was sent successfully
    if ($mail && $mail->send()) {
        echo json_encode(['status' => 'success', 'message' => 'A password reset link has been sent to your email.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send email.']);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
