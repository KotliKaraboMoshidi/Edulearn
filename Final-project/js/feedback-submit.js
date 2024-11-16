$(document).ready(function() {
    $('#feedback-form').on('submit', function(e) {
        e.preventDefault(); // Prevent normal form submission

        const feedback = $('#feedback').val(); // Get feedback text

        $.ajax({
            url: 'ajax/feedback.php', // PHP file to handle feedback
            method: 'POST',
            data: { feedback: feedback },
            success: function(response) {
                try {
                    const result = JSON.parse(response); // Parse JSON response
                    $('#message').text(result.message); // Show success/error message

                    if (result.status === 'success') {
                        $('#message').css('color', 'green');
                        $('#feedback-form')[0].reset(); // Clear form on success
                    } else {
                        $('#message').css('color', 'red');
                    }
                } catch (e) {
                    $('#message').text('Unexpected server response.');
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
