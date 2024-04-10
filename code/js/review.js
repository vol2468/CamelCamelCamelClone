$(document).ready(function () {

    let visibleItems = 3;

    // Load reviews initially
    loadReviews();

    // Checks if button needs to be visible
    // if ($('#reviews-container .review').length === 0) {
    //     $('#load-more-reviews-btn').hide();
    // }

    $('#load-more-reviews-btn').click(function () {
        visibleItems += 3;
        $('#reviews-container .review:lt(' + visibleItems + ')').show();

        // Hide if no more items
        if (visibleItems >= $('#reviews-container .review').length) {
            $(this).hide();
        }
    });

    // Submit review
    $('#add-review').submit(function (event) {
        event.preventDefault();

        $.ajax({
            url: 'submit_review.php',
            type: 'POST',
            data: $(this).serialize() + '&uid=' + uid + '&pid=' + pid,
            success: function () {
                loadReviews(); // Refresh reviews
            },
            error: function () {
                console.error("Failed to submit review");
            }
        });
    })

    $('#submit').click(function () {
        // Reset filter to "All Ratings"
        $('#rating-filter').selectedIndex = "0";
    });

    // Function to get reviews from server and inject into product.php
    function loadReviews() {
        $.ajax({
            url: 'get_reviews.php',
            type: 'GET',
            data: 'pid=' + pid,
            success: function (data) {
                $('#reviews-container').html(data);
            },
            error: function () {
                console.error("Failed to load reviews");
            }
        });
    }
});
