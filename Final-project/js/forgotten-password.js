$(document).ready(function () {
    $('#forgotten-password-form').on('submit', function (e) {
        e.preventDefault();

        const email = $('#email').val().trim();
        $('#message').text(''); // Clear previous messages

        $.ajax({
            url: 'ajax/forgotten-password.php',
            type: 'POST',
            data: { email: email },
            success: function (response) {
                console.log('Response:', response); // Log the response to debug

                try {
                    const result = typeof response === "string" ? JSON.parse(response) : response;
                    
                    if (result.status === 'success') {
                        $('#message').text(result.message).css('color', 'green');
                        setTimeout(() => {
                            window.location.href = 'sign-in.html';
                        }, 3000);
                    } else {
                        $('#message').text(result.message).css('color', 'red');
                    }
                } catch (error) {
                    console.error('JSON parsing error:', error);
                    $('#message').text('Error parsing response.').css('color', 'red');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('AJAX error:', textStatus, errorThrown);
                $('#message').text('An error occurred while processing your request. Please try again.').css('color', 'red');
            }
        });
    });
});
