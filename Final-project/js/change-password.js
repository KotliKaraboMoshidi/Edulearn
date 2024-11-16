$('#change-password-form').on('submit', function(e) {
    e.preventDefault();
    
    const currentPassword = $('#current-password').val();
    const newPassword = $('#new-password').val();
    const confirmPassword = $('#confirm-password').val();

    // Clear previous messages
    $('#message').text('').css('color', '');

    // Validate form fields
    if (!currentPassword || !newPassword || !confirmPassword) {
        $('#message').text('All fields are required.');
        $('#message').css('color', 'red');
        return;
    }

    if (newPassword !== confirmPassword) {
        $('#message').text('New passwords do not match.');
        $('#message').css('color', 'red');
        return;
    }

    // Send request to change the password
    $.post('ajax/change-password.php', {
        current_password: currentPassword,
        new_password: newPassword,
        confirm_password: confirmPassword
    }).done(function(response) {
        const result = JSON.parse(response);

        $('#message').text(result.message);  // Display the result message
        if (result.status === 'success') {
            $('#message').css('color', 'green');
            $('#change-password-form')[0].reset();  // Reset form on success
        } else {
            $('#message').css('color', 'red');
        }
    }).fail(function() {
        $('#message').text('An error occurred while changing the password. Please try again.');
        $('#message').css('color', 'red');
    });
});
