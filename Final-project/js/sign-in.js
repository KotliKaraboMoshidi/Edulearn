$(document).ready(function () {
    // Handle the form submission
    $("#sign-in-form").on("submit", function (e) {
        e.preventDefault();

        // Clear previous messages
        $("#error-message").hide("slow").text("");
        $("#success-message").hide("slow").text("");

        // Gather form data
        let username = $("#username").val().trim();
        let password = $("#password").val().trim();

        // Validate inputs
        if (!username || !password) {
            $("#error-message").text("Username and password are required").show("slow");
            return;
        }

        // Form data
        let formData = {
            username: username,
            password: password
        };

        // AJAX request
        $.ajax({
            url: "ajax/sign-in.php",
            method: "POST",
            data: formData,
            beforeSend: function () {
                // Show spinner and adjust opacity
                $(".card").css("opacity", "0.5");
                $(".spinner-overlay").show("slow");
                $("#sign-in-btn").html("Loading ...").prop("disabled", true);
            },
            success: function (response) {
                console.log("Response:", response); // Log response for debugging
                try {
                    var jsonResponse = JSON.parse(response);
                    if (jsonResponse.success) {
                        $("#success-message")
                            .text(jsonResponse.message)
                            .show("slow");
                        $("#sign-in-form").trigger("reset");
                        $("#error-message").hide("slow");
                        // Redirect to the dashboard.html
                        window.location.href = jsonResponse.redirect;
                    } else {
                        $("#error-message")
                            .text(jsonResponse.message)
                            .show("slow");
                        $("#success-message").hide("slow");
                    }
                } catch (e) {
                    console.log(e);
                    $("#error-message").html(response).show("slow"); // Display raw response for debugging
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                $("#error-message")
                    .text("An error occurred: " + textStatus)
                    .show("slow");
                $("#success-message").hide("slow");
            },
            complete: function () {
                // Hide spinner and restore opacity
                $(".card").css("opacity", "1");
                $(".spinner-overlay").hide("slow");
                $("#sign-in-btn").html("Sign In").prop("disabled", false);
            }
        });
    });
});
