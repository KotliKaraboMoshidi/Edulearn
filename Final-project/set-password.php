<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/set-password.js"></script>
</head>
<body>
    <?php
    // Retrieve PIN and token from the URL
    if (isset($_GET['pin']) && isset($_GET['token'])) {
        $pin = htmlspecialchars($_GET['pin']);
        $token = htmlspecialchars($_GET['token']);
    } else {
        echo "<p class='error-message'>Invalid reset link. Missing pin or token.</p>";
        exit;
    }
    ?>
    <div class="container">
        <div class="card">
            <h2>Set New Password</h2>
            <form id="set-password-form">
                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <input type="password" id="new-password" name="new-password" required>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>
                </div>
                <!-- Hidden fields for PIN and token -->
                <input type="hidden" id="pin" name="pin" value="<?php echo $pin; ?>">
                <input type="hidden" id="token" name="token" value="<?php echo $token; ?>">
                <button type="submit">Set Password</button>
                <div id="message"></div>
            </form>
        </div>
    </div>
</body>
</html>
