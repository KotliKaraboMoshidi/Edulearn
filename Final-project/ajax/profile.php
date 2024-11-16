<?php
session_start();

// Include the database configuration
require '../includes/config.php';

// Check if the user is logged in by checking the session
if (isset($_SESSION['user_id'])) {
    // User is logged in, fetch their details from the database
    $userId = $_SESSION['user_id'];

    // Debugging step: Check if the session variable is available
    // Uncomment this line to debug
    // echo "User ID: " . $userId;

    // Create a connection to the database
    $conn = connect_db();

    // Query the database to get the user's details
    $query = "SELECT username, email, created_at FROM users WHERE id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the user exists, return their details as a JSON response
    if ($user) {
        echo json_encode([
            'logged_in' => true,
            'username' => $user['username'],
            'email' => $user['email'],
            'created_at' => $user['created_at']
        ]);
    } else {
        echo json_encode(['logged_in' => false, 'error' => 'User not found.']);
    }
} else {
    // User is not logged in
    echo json_encode(['logged_in' => false, 'error' => 'No session found.']);
}
?>
