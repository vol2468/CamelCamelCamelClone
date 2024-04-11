$(document).ready(function () {

    let visibleItems = 4;
    const ratingFilter = $('#rating-filter');

    // Load reviews initially
    loadReviews();

    // Filter review by rating
    ratingFilter.on('change', filterReviews);

    // Load more reivews
    $('#load-more-reviews-btn').click(function () {
        visibleItems += 4;
        $('#reviews-container .review:lt(' + visibleItems + ')').show();

        // Hide if no more items
        if (visibleItems >= $('#reviews-container .review').length) {
            $(this).hide();
        }
    });

    // Delete review
    $(document).on('submit', 'form.delete-btn-form', function (event) {
    
        if (confirm("Are you sure you want to delete this review?")) {
            event.preventDefault();
            
            // Get the hidden value
            const d_uid = $(this).find('input[name="delete-uid"]').val();
            const d_uidAsInt = parseInt(d_uid, 10); // Convert to integer 

            $.ajax({
                url: 'delete_review.php',
                type: 'POST',
                data: 'd_uid=' + d_uidAsInt + '&pid=' + pid,
                success: function (count) {
                    loadReviews(); // Refresh reviews 
                    $('.reviews-title-container i').html("(" + count + ")");
                },
                error: function () {
                    console.error("Failed to delete review");
                }
            });
        } else {
            console.log("Action canceled.");
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
            data: 'pid=' + pid + "&uid=" + uid + "&usertype=" + usertype,
            success: function (data) {
                $('#reviews-container').html(data);

                // Checks if button needs to be visible after loading reviews
                if ($('#reviews-container .review').length == 0) {
                    $('#load-more-reviews-btn').hide();
                } else {
                    $('#load-more-reviews-btn').show();
                }
            },
            error: function () {
                console.error("Failed to load reviews");
            }
        });
    }

    // Function for filtering reviews by rating
    function filterReviews() {
        const rating = ratingFilter.val(); // Rating is string containing int
        const ratingAsInt = parseInt(rating, 10); // Convert to integer 

        $.ajax({
            url: 'filter_reviews.php',
            type: 'GET',
            data: 'pid=' + pid + "&uid=" + uid + "&rating=" + ratingAsInt,
            success: function (data) {
                $('#reviews-container').html(data);

                // Reset number of visible items whenever filter is used
                visibleItems = 4;

                // Check button visiblity
                if (visibleItems >= $('#reviews-container .review').length) {
                    $('#load-more-reviews-btn').hide();
                } else {
                    $('#load-more-reviews-btn').show();
                }
            },
            error: function () {
                console.error("Failed to load reviews");
            }
        });
    }
});
