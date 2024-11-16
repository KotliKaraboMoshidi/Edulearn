$(document).ready(function () {
    $('#set-password-form').on('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission behavior

        // Gather form data
        const newPassword = $('#new-password').val().trim();
        const confirmPassword = $('#confirm-password').val().trim();
        const pin = $('#pin').val().trim();
        const token = $('#token').val().trim();

        // Validate passwords match
        if (newPassword !== confirmPassword) {
            $('#message').text("Passwords do not match.").css('color', 'red');
            return;
        }

        // Validate non-empty fields
        if (!newPassword || !confirmPassword || !pin || !token) {
            $('#message').text("All fields are required.").css('color', 'red');
            return;
        }

        // Send data via AJAX
        $.ajax({
            url: 'ajax/set-password.php', // Ensure the file path is correct
            type: 'POST',
            data: {
                new_password: newPassword,
                confirm_password: confirmPassword,
                pin: pin,
                token: token
            },
            dataType: 'json', // Expecting a JSON response
            success: function (response) {
                if (response.status === 'success') {
                    $('#message').text(response.message).css('color', 'green');
                    setTimeout(() => window.location.href = 'sign-in.html', 3000); // Redirect after 3 seconds
                } else {
                    $('#message').text(response.message).css('color', 'red');
                }
            },
            error: function (xhr, status, error) {
                // Display a user-friendly error message
                $('#message').text('An error occurred. Please try again.').css('color', 'red');

                // Log detailed error information for debugging
                console.log('AJAX Error:');
                console.log('Error:', error);
                console.log('Status:', status);
                console.log('Response Text:', xhr.responseText); // Log the server's response
            }
        });
    });
});
