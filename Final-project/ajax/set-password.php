<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the config file for database connection
require '../includes/config.php';

// Set the header for JSON response
header('Content-Type: application/json');

// Establish the database connection
$conn = connect_db();  // Use the connect_db function to get the PDO connection

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input data
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $pin = trim($_POST['pin']);
    $token = trim($_POST['token']);

    // Validate input
    if (empty($newPassword) || empty($confirmPassword) || empty($pin) || empty($token)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    // Check if passwords match
    if ($newPassword !== $confirmPassword) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
        exit;
    }

    // Query reset_password table to validate PIN and token
    $stmt = $conn->prepare("SELECT user_id, expires_at FROM reset_password WHERE pin = :pin AND token = :token LIMIT 1");
    $stmt->bindParam(':pin', $pin);
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    // Fetch the result
    $resetData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resetData) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid or expired reset link.']);
        exit;
    }

    // Check if the token has expired
    if (strtotime($resetData['expires_at']) < time()) {
        echo json_encode(['status' => 'error', 'message' => 'Reset link has expired.']);
        exit;
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Update the user's password
    $updateStmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
    $updateStmt->bindParam(':password', $hashedPassword);
    $updateStmt->bindParam(':id', $resetData['user_id']);

    if ($updateStmt->execute()) {
        // Delete reset request after successful password reset
        $deleteStmt = $conn->prepare("DELETE FROM reset_password WHERE user_id = :user_id");
        $deleteStmt->bindParam(':user_id', $resetData['user_id']);
        $deleteStmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Password successfully updated.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update password.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
