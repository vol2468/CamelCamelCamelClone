$(document).ready(function () {

    function loadNumUsers() {
        $.ajax({
            url: 'get_number_of_users.php',
            type: 'GET',
            success: function (data) {
                $('#user-count').html(data);
            },
            error: function () {
                console.error("Failed to load number of users.");
            }
        });
    }
    loadNumUsers(); // Load number of users
});
