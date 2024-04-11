$(document).ready(function () {

    

    // Function to automatcally check for updates on review and send users alert accordingly
    function checkPriceWatchUpdates() {
        
        $.ajax({
            url: 'check_price_watch_updates.php',
            type: 'GET',
            data: 'uid=' + uid,
            success: function (response) {
                if (response) {
                    alert(response);
                    // console.log(response);
                }
            },
            error: function () {
                console.error("Failed to check price watch updates!");
            }
        }).always(function () {
            setTimeout(checkPriceWatchUpdates, 7000); // Check again in 7 seconds
        });
    } 
    checkPriceWatchUpdates(); // Start initial check
});