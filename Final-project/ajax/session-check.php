<?php
session_start();
require '../includes/config.php';

$response = ['logged_in' => false];

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $response['logged_in'] = true;

    // Fetch additional user details from the database
    $pdo = connect_db();
    $stmt = $pdo->prepare("SELECT email, created_at FROM users WHERE username = :username");
    $stmt->execute([':username' => $_SESSION['username']]);
    $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userDetails) {
        $response['username'] = $_SESSION['username'];
        $response['email'] = $userDetails['email'];
        $response['created_at'] = $userDetails['created_at'];
    } else {
        $response['error'] = "Unable to retrieve user details.";
    }
} else {
    header('Location: /whoops.html');
    exit();
}

echo json_encode($response);

