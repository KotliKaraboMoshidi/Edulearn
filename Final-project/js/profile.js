$(document).ready(function () {
    $.ajax({
        url: 'ajax/profile.php',  // The endpoint to check user session
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            if (data.logged_in === false) {
                window.location.href = 'sign-in.html';  // Redirect to the login page if not logged in
            } else {
                // Log the data response to see what is being returned
                console.log(data);

                // Check if the data is valid
                if (data.username && data.email && data.created_at) {
                    // Update the profile page with user details
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
            window.location.href = 'sign-in.html';  // Redirect to the login page in case of error
        }
    });
});
