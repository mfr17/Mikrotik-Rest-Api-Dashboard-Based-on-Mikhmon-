$(document).ready(function () {
    // Function to fetch real-time data and update the card
    function fetchRealtimeData() {
        $.ajax({
            url: '/realtime-data', // Adjust URL as per your route configuration
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                // Assuming response contains 'uptime_formatted' and 'cpu_load'
                console.log(response);
                var uptimeFormatted = response.resource.uptime_formatted;
                var cpuLoad = response.resource['cpu-load'];
                var date = response.clock.date;
                var time = response.clock.time;

                // Update the x-card component using JavaScript
                $('#system').html(`
                        <x-card type="text" icon="heroicon-o-desktop-computer" bgColor="bg-green-200" title="System"
                            :desc="[
                                'CPU Load: ${cpuLoad}%' , // Assuming cpuLoad is the CPU load data
                                'Uptime: ${uptimeFormatted}', // Assuming uptimeFormatted is the uptime data
                                'Date: ${date} ${time}'
                            ]" />
                    `);
            },
            error: function (xhr, status, error) {
                console.error('Failed to fetch real-time data:', error);
                // Handle error scenario as needed
            }
        });
    }

    // Initial fetch
    fetchRealtimeData();

    // Fetch data every second (1000 milliseconds)
    setInterval(fetchRealtimeData, 1000);
});

$(document).ready(function () {
    var chart;
    var currentData = {}; // Object to store the latest data for each interface

    // Function to create the chart
    function createChart() {
        chart = Highcharts.chart('traffic-chart', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Interface Traffic: '
            },
            xAxis: {
                type: 'datetime',
                title: {
                    text: 'Time'
                }
            },
            yAxis: {
                title: {
                    text: 'Speed'
                },
                labels: {
                    formatter: function () {
                        return formatBites(this.value);
                    }
                }
            },
            series: [{
                name: 'Upload',
                data: []
            }, {
                name: 'Download',
                data: []
            }],
            credits: {
                enabled: false
            }
        });
    }

    // Function to update the chart with new data
    function updateChart(interface) {
        $.ajax({
            url: '/traffic/' + interface,
            method: 'GET',
            success: function (response) {
                if (!response.error) {
                    console.log(response);
                    var time = new Date().getTime();
                    const txData = parseFloat(response.data.tx.value);
                    const rxData = parseFloat(response.data.rx.value);

                    // Add new data to the existing series data
                    chart.series[0].addPoint([time, txData], true, chart.series[0].points
                        .length >= 5);
                    chart.series[1].addPoint([time, rxData], true, chart.series[1].points
                        .length >= 5);

                    // Store the latest data for the interface
                    currentData[interface] = chart.series.map((series) => ({
                        name: series.name,
                        data: series.options.data.slice(-
                            10) // Keep only the last 10 data points
                    }));

                    // Update the chart title with the selected interface
                    chart.setTitle({
                        text: 'Interface Traffic: ' + escapeHtml(interface)
                    });
                } else {
                    console.error(response.error);
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    }

    // Function to escape HTML special characters
    function escapeHtml(unsafe) {
        return unsafe.replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Function to format bytes
    function formatBites(size) {
        const units = ['bps', 'kbps', 'Mbps', 'Gbps', 'Tbps', 'Pbps', 'Ebps', 'Zbps', 'Ybps'];
        let unitIndex = 0;
        while (size >= 1000 && unitIndex < units.length - 1) {
            size /= 1000;
            unitIndex++;
        }
        return size.toFixed(2) + ' ' + units[unitIndex];
    }

    // Create the chart when the document is ready
    createChart();

    // Set default interface when the page is loaded
    var defaultInterface = $('#interface-select').val();
    updateChart(defaultInterface);

    // Update the chart every 3 seconds
    setInterval(function () {
        var selectedInterface = $('#interface-select').val();
        updateChart(selectedInterface);
    }, 3000);

    // Event handler for changing the selected interface
    $('#interface-select').change(function () {
        var selectedInterface = $(this).val();

        // If data for the selected interface is already available, use it
        if (currentData[selectedInterface]) {
            chart.series.forEach((series, index) => {
                series.setData(currentData[selectedInterface][index].data, true);
            });
            // Update the chart title with the selected interface
            chart.setTitle({
                text: 'Interface Traffic: ' + escapeHtml(selectedInterface)
            });
        } else {
            updateChart(selectedInterface);
        }
    });
});
