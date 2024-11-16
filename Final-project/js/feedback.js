$(document).ready(function() {
    $('#feedback-form').on('submit', function(e) {
        e.preventDefault(); // Prevent form from submitting normally

        const feedback = $('#feedback').val(); // Get the value from the textarea

        $.ajax({
            url: 'ajax/feedback.php', // Path to the PHP script that handles the submission
            method: 'POST',
            data: {
                feedback: feedback
            },
            success: function(response) {
                const result = JSON.parse(response); // Parse the response JSON
                $('#message').text(result.message); // Display the response message

                if (result.status === 'success') {
                    $('#message').css('color', 'green');
                    $('#feedback-form')[0].reset(); // Reset the form after successful submission
                } else {
                    $('#message').css('color', 'red');
                }
            },
            error: function(xhr, status, error) {
                $('#message').text('Error: Could not submit feedback.');
                $('#message').css('color', 'red');
            }
        });
    });
});
