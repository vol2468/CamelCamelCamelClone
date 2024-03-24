$(document).ready(function () {
    $("#register-form").submit(function (event) {
        event.preventDefault(); // Prevent default form submission

        var hasEmptyField = false;

        // Check each input field for empty values
        $(".required").each(function () {
            if ($(this).val().trim() === "") {
                $(this).css("background-color", "#FFCCCB"); // Highlight empty field with light red
                hasEmptyField = true;

                // Get field name from corresponding input's "name" attribute
                var fieldName = $(this).attr("name");

                // Display error message below the field in red
                $(this).next(".error-message").css("color", "red").text(fieldName + " is required!");
                $(this).attr("placeholder", "")
            } else {
                $(this).css("background-color", "white"); // Reset background color
                $(this).next(".error-message").text(""); // Clear error message
                hasEmptyField = true; // Reset hasEmptyField
            }
        });

        // Check if password matches re-entered password
        var password = $("#password").val();
        var confirmPassword = $("#confirm-password").val();

        if (password !== confirmPassword) {
            $("#message").html("Passwords do not match!").css("color", "red");
            return false; // Stop form submission
        } else {
            $("#message").html("");
            // ... AJAX code to submit the registration data ...
        }

        if (hasEmptyField) {
            $("#warning-mssage").hide(); // Hide general warning message
            return false; // Prevent form submission
        } else {
            // If no empty fields, use AJAX to submit the form data
            // ... AJAX code here ...
        }
    });
});
