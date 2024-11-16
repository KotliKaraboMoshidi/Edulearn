<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgotten Password</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to main CSS file for consistent styling -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/forgotten-password.js"></script> <!-- Link to the AJAX script -->
</head>
<body>

<div class="container-Fpassword">
    <h2>Forgot Password</h2>
    <p>Please enter your email address to reset your password.</p>

    <form id="forgotten-password-form">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <button type="submit" id="submit-btn">Reset Password</button>
        </div>

        <div id="message"></div> <!-- Message display area -->
    </form>

    <p><a href="sign-in.html">Back to Sign In</a></p>
</div>


</body>
</html>
