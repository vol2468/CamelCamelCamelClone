$(document).ready(function () {
    
    initializeChart();
    updateChart();

});

// TODO: fix chart to show date nicely also for time interval.
function initializeChart() {
    const priceHistory = document.getElementById("price-history");

    chart = new Chart(priceHistory, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: "Price Transition",
                data: [],
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        }, 
        options: {
            scales: {
                x: {
                    type: 'time',
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    ticks: {
                        callback: function(value, index, values) {
                            if (Math.floor(value) === value) {
                                return value;
                            }
                        },
                        min: 0 // this ensures the minimum value is 0
                    }
                    
                }
            },
            tooltips: {
                intersect: false,
                mode: 'index',
            },
            responsive: true
        }
    });
}

function updateChart() {
    var pid = document.getElementById('price-history').getAttribute('data-pid');

    $.ajax({
        url: 'fetchProductHistoryData.php', 
        type: 'POST',
        data: {pid: pid},
        dataType: 'json',
        success: function(data) {
            chart.data.labels = data[0];
            chart.data.datasets[0].data = data[1]; 
            chart.update(); 
        },
        error: function(xhr, status, error) {
          console.error("An AJAX error occured: " + status + "\nError: " + error);
        }
    });
}