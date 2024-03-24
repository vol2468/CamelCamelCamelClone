$(document).ready(function() {
    // Initial dropdown population
    populateDropdown();

    // Periodically check for updates
    setInterval(checkForUpdates, 5000); // Check every 5 seconds
});

function populateDropdown() {
    $.ajax({
        url: 'php/fetch_categories.php',
        type: 'GET',
        dataType: 'json', // Expecting JSON response
        success: function(categories) {
            $('#category-dropdown').empty(); // Clear old options
            $.each(categories, function(key, value) {
                $('#category-dropdown').append(`<option value="${value.cid}">${value.cname}</option>`);
            });
        },
        error: function() {
            console.error("Error fetching categories");
        }
    });
}

function checkForUpdates() {
    $.ajax({
        url: 'php/check_for_updates.php',
        type: 'GET',
        success: function(needsUpdate) {
            if (needsUpdate) {
                populateDropdown(); // Refetch and update dropdown
            }
        }
    });
}
