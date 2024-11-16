<?php
session_start();
require '../includes/config.php';

// Database connection
$conn = connect_db();

// Check if the user is logged in by verifying session ID
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in. Please log in to change your password.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = trim($_POST['current_password']);
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $userId = $_SESSION['user_id']; // Using `user_id` from the session for the user ID

    if ($newPassword !== $confirmPassword) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
        exit;
    }

    // Fetch user's current password from the database
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = :id");
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['status' => 'error', 'message' => 'User not found. Please log in again.']);
        exit;
    }

    // Verify if the provided current password is correct
    if (!password_verify($currentPassword, $user['password'])) {
        echo json_encode(['status' => 'error', 'message' => 'Incorrect current password.']);
        exit;
    }

    // Hash and update the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    $updateStmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
    $updateStmt->bindParam(':password', $hashedPassword);
    $updateStmt->bindParam(':id', $userId, PDO::PARAM_INT);

    if ($updateStmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Password successfully updated.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update password.']);
    }
}
?>
