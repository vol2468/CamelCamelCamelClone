$(document).ready(function () {

    $('#add-to-pwatch-btn').click(function () {

        $.ajax({
            url: 'add_to_price_watch.php',
            type: 'POST',
            data: 'uid=' + uid + '&pid=' + pid,
            success: function (response) {
                alert(response);
            },
            error: function (error) {
                console.error('Error: ', error);
            }
        });
    });
});
