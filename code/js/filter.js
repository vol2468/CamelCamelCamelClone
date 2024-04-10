$(document).ready(function () {
    const ratingFilter = $('#rating-filter');

    ratingFilter.on('change', filterReviews);

    function filterReviews() {
        const rating = ratingFilter.val(); // Rating is string containing int
        const ratingAsInt = parseInt(rating, 10); // Convert to integer 

        $.ajax({
            url: 'filter_reviews.php',
            type: 'GET',
            data: 'pid=' + pid + "&rating=" + ratingAsInt,
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
});



