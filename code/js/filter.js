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
                console.log(data);
                $('#reviews-container').html(data);
            },
            error: function () {
                console.error("Failed to load reviews");
            }
        });
    }
});



