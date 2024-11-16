$(document).ready(function () {
    // Perform AJAX request to check session and retrieve profile details
    $.ajax({
        url: 'ajax/session-check.php',  // Endpoint to check session and retrieve profile data
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            if (data.logged_in === false) {
                window.location.href = 'sign-in.html';  // Redirect to sign-in page if not logged in
            } else {
                // Log the data response to see what is being returned
                console.log(data);

                // Check if the data is valid
                if (data.username && data.email && data.created_at) {
                    // Update welcome message and profile details
                    $('#welcome-message').text(`Welcome, ${data.username}!`);
                    $('#username').text(data.username);
                    $('#email').text(data.email);
                    $('#member-since').text(data.created_at); // Format if needed
                } else {
                    console.error("Error: Data missing from response.");
                    $('#message').text("Error: Unable to fetch user details.");
                }
            }
        },
        error: function (xhr, status, error) {
            console.error('Error checking session:', error);
            window.location.href = 'sign-in.html';  // Redirect to sign-in page on error
        }
    });
});
