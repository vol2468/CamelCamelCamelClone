$(document).ready(function () {

    let lastRowCount = -1;

    function checkForUpdates() {
        $.ajax({
            url: 'check_review_updates.php',
            dataType: 'json',
            success: function (data) {
                if (data.rowCount !== lastRowCount && lastRowCount > -1) {
                    alert('The database table has been updated!');
                }
                lastRowCount = data.rowCount;
            }
        }).always(function () {
            setTimeout(checkForUpdates, 5000); // Check again in 5 seconds
        });
    }
    checkForUpdates(); // Start initial check
});