<?php
session_start();  // Start the session
require '../includes/config.php';  // Include database connection and utilities

// Set the response array
$response = ['success' => false, 'message' => 'An error occurred. Please try again.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    // Clean the inputs using clean_input from config.php
    $username = clean_input($_POST['username']);
    $password = clean_input($_POST['password']);

    if (empty($username) || empty($password)) {
        $response['message'] = 'Username and password are required.';
        echo json_encode($response);
        exit();
    }

    // Connect to the database
    $pdo = connect_db();

    // Check if the user exists
    try {
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Correct password, set session variables
            $_SESSION['user_id'] = $user['id'];  // Store user id in session
            $_SESSION['username'] = $username;  // Optionally store the username

            // Set success response with redirection to the dashboard
            $response['success'] = true;
            $response['message'] = 'Sign-in successful!';
            $response['redirect'] = 'dashboard.html';  // Redirect after successful login
        } else {
            // Incorrect credentials
            $response['message'] = 'Invalid username or password.';
        }
    } catch (PDOException $e) {
        // Handle database errors
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid request.';
}

echo json_encode($response);  // Return response as JSON
?>
