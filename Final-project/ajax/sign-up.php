<?php

require '../includes/config.php';

// Set the response array
$response = ['success' => false, 'message' => 'An error occurred. Please try again.'];

// Check if the request is a POST request and the required fields are set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    // Sanitize input
    $username = clean_input($_POST['username']);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = clean_input($_POST['password']);

    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        $response['message'] = 'All fields are required.';
        echo json_encode($response);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email format.';
        echo json_encode($response);
        exit;
    }

    if (strlen($password) < 8) {
        $response['message'] = 'Password must be at least 8 characters long.';
        echo json_encode($response);
        exit;
    }

    // Connect to the database
    $pdo = connect_db();

    // Check for duplicate email
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $response['message'] = 'Email address is already registered.';
            echo json_encode($response);
            exit;
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
        echo json_encode($response);
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user into the database
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashedPassword]);

        // Define email details
        $subject = "Welcome to EduLearn!";
        $template_path = '../includes/email-template.html';
        $placeholders = [
            'username' => htmlspecialchars($username),
            'rooturl' => $_ENV['APP_URL_ROOT']
        ];

        // Send email
        require '../vendor/autoload.php';
        $mail = send_email($email, $username, $subject, $template_path, $placeholders);

        if ($mail && $mail->send()) {
            $response['success'] = true;
            $response['message'] = 'Registration successful! Please check your email.';
        } else {
            $response['message'] = 'Registration successful, but failed to send email.';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid request.';
}

// Return the response as JSON
echo json_encode($response);
