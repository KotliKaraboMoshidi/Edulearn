<?php

use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '../../');
$dotenv->load();

// Database configuration
$DB_HOST = $_ENV['DB_HOST'];
$DB_PORT = $_ENV['DB_PORT'];
$DB_USERNAME = $_ENV['DB_USERNAME'];
$DB_PASSWORD = $_ENV['DB_PASSWORD'];
$DB_DATABASE = $_ENV['DB_DATABASE'];

// SMTP configuration for Sendmail
$SMTP_HOST = $_ENV['EMAIL_HOST'];
$SMTP_PORT = $_ENV['EMAIL_PORT'];
$SMTP_USERNAME = $_ENV['EMAIL_USER'];
$SMTP_PASSWORD = $_ENV['EMAIL_PASS'];

// Connect to the database
function connect_db() {
    global $DB_HOST, $DB_PORT, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE;
    try {
        $pdo = new PDO("mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_DATABASE", $DB_USERNAME, $DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }
}

// Send email function with customizable subject and template
function send_email($recipient_email, $recipient_name, $subject, $template_path, $placeholders = []) {
    global $SMTP_HOST, $SMTP_PORT, $SMTP_USERNAME, $SMTP_PASSWORD;
    $mail = new PHPMailer(true);

    try {
        // SMTP server settings
        $mail->isSMTP();
        $mail->Host = $SMTP_HOST;
        $mail->Port = $SMTP_PORT;
        $mail->SMTPAuth = true;
        $mail->Username = $SMTP_USERNAME;
        $mail->Password = $SMTP_PASSWORD;
        $mail->SMTPSecure = 'tls';

        // Sender and recipient
        $mail->setFrom("from@example.com", "Edulearn");
        $mail->addAddress($recipient_email, $recipient_name);
        $mail->addReplyTo('noreply@example.com', 'Edulearn');

        // Load and customize the email template
        $template = file_get_contents($template_path);
        foreach ($placeholders as $key => $value) {
            $template = str_replace("[$key]", htmlspecialchars($value), $template);
        }

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->msgHTML($template);
        $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $template));

        return $mail;

    } catch (Exception $e) {
        return false;
    }
}

// Sanitize input
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim(strip_tags($data))));
}
