$(document).ready(function() {

    function loadReviews() {
        $.ajax({
            url: 'get_reviews.php',
            type: 'GET',
            data: 'pid=' + pid, 
            success: function(data) {
                $('#reviews-container').html(data);
            },
            error: function() {
                console.error("Failed to load reviews");
            }
        });
    }

    loadReviews(); // Load reviews initially

    $('#add-review').submit(function(event) {
        event.preventDefault();

        $.ajax({
            url: 'submit_review.php', 
            type: 'POST',
            data: $(this).serialize() + '&uid=' + uid + '&pid=' + pid,
            success: function() {
                loadReviews(); // Refresh reviews
            },
            error: function() {
                console.error("Failed to submit review");
            }
        });
    });
});
