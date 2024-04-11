var chart;

$(document).ready(function () {
    
    loadNumUsers(); // Load number of users
    loadNumTickets(); // Load number of tickets
    initializeChart();
    updateChart();

});

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

function loadNumTickets() {
    $.ajax({
        url: 'get_number_of_tickets.php',
        type: 'GET',
        success: function (data) {
            $('#ticket-count').html(data);
        },
        error: function () {
            console.error("Failed to load number of tickets.");
        }
    });
}

function initializeChart() {
    const traffic = document.getElementById("traffic");
    const labels = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"];

    chart = new Chart(traffic, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: "Website Traffic",
                data: [],
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        }, 
        options: {
            scales: {
                y: {
                    min: 0, // This ensures the minimum value is 0.
                    max: 20, // This sets the maximum value to at least 20.
                    beginAtZero: true,
                    ticks: {
                        // Include a dollar sign in the ticks and ensure only whole numbers are used
                        callback: function(value, index, values) {
                            if (Math.floor(value) === value) {
                                return value;
                            }
                        },
                        stepSize: 1, // this ensures the step interval is 1
                        min: 0 // this ensures the minimum value is 0
                    }
                }
            }
        }
    });
}

function updateChart() {
    $('#yearSelector').change(function() {
        var selectedYear = $(this).val();
        $.ajax({
          url: 'fetchLoginHistoryData.php', 
          type: 'POST',
          data: {selectedYear: selectedYear},
          dataType: 'json',
          success: function(data) {
            chart.data.datasets[0].data = data; 
            chart.update(); 
          },
          error: function(xhr, status, error) {
            console.error("An AJAX error occured: " + status + "\nError: " + error);
          }
        });
    });
    $('#yearSelector').trigger('change');
}
