<?php
session_start();
require '../includes/config.php'; // Make sure this path is correct

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You need to be logged in to submit feedback.']);
    exit;
}

// Validate feedback content
$feedbackText = trim($_POST['feedback']);
if (empty($feedbackText)) {
    echo json_encode(['status' => 'error', 'message' => 'Please provide your feedback.']);
    exit;
}

// Connect to the database
$conn = connect_db();
$userId = $_SESSION['user_id']; // Get the user ID from session

try {
    // Prepare and execute the feedback insertion query
    $stmt = $conn->prepare("INSERT INTO feedback (user_id, feedback_text) VALUES (:user_id, :feedback_text)");
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':feedback_text', $feedbackText, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Feedback submitted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to submit feedback. Please try again.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}

$conn = null; // Close the database connection
?>
