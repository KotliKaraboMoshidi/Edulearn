$(document).ready(function() {
    // Check session to ensure the user is logged in
    $.get('ajax/session-check.php', function(response) {
        if (!response.logged_in) {
            // Redirect to whoops.html if not logged in
            window.location.href = 'whoops.html';
        } else {
            // Show the username in the welcome message
            $('#welcome-message').text(`Welcome, ${response.username}!`);
        }
    }, 'json');

    // Add hover effect to cards
    $('.kpi div').hover(
        function() {
            $(this).css({
                'transform': 'scale(1.05)',
                'transition': 'transform 0.3s'
            });
        },
        function() {
            $(this).css('transform', 'scale(1)');
        }
    );
});