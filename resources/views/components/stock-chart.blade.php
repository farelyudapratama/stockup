<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@1.2.1/dist/chartjs-plugin-zoom.min.js"></script>
    <style>
        body {
            font-family: 'Inter', 'Arial', sans-serif;
            margin: 0;
            background-color: #f4f6f9;
            color: #333;
        }
        #container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        canvas {
            max-width: 100%;
            height: 500px !important;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        .chart-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            gap: 10px;
        }
        .chart-controls button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            flex-grow: 1;
        }
        .chart-controls button:hover {
            background-color: #2980b9;
        }
        .chart-controls select {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
            flex-grow: 2;
        }
        .zoom-controls {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div id="container">
        <h1>Stock Management Dashboard</h1>
        <div class="chart-controls">
            <select id="productSelect">
                <option value="">All Products</option>
                <!-- Options will be dynamically populated -->
            </select>
            <div class="zoom-controls">
                <button id="zoomIn">Zoom In</button>
                <button id="zoomOut">Zoom Out</button>
                <button id="resetZoom">Reset View</button>
            </div>
        </div>
        <canvas id="stockChart"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simplified data preparation
            const rawChartData = @json($chartData);
            const dates = @json($dates);

            // Color palette for better visual distinction
            const colorPalette = [
                { in: 'rgba(75, 192, 192, 0.6)', out: 'rgba(255, 99, 132, 0.6)' },
                { in: 'rgba(54, 162, 235, 0.6)', out: 'rgba(255, 159, 64, 0.6)' },
                { in: 'rgba(153, 102, 255, 0.6)', out: 'rgba(255, 205, 86, 0.6)' }
            ];

            // Populate product select
            const productSelect = document.getElementById('productSelect');
            const productNames = [...new Set(rawChartData.map(item => item.product_name))];
            productNames.forEach(name => {
                const option = document.createElement('option');
                option.value = name;
                option.textContent = name;
                productSelect.appendChild(option);
            });

            // Prepare chart configuration
            const ctx = document.getElementById('stockChart').getContext('2d');
            let stockChart;

            function createChart(filteredData) {
                // Destroy existing chart if it exists
                if (stockChart) {
                    stockChart.destroy();
                }

                // Prepare datasets
                const datasets = filteredData.flatMap((data, index) => {
                    const colors = colorPalette[index % colorPalette.length];
                    return [
                        {
                            label: `${data.product_name} - Stock In`,
                            data: data.stock_in,
                            backgroundColor: colors.in,
                            borderColor: colors.in.replace('0.6', '1'),
                            borderWidth: 1,
                            type: 'bar'
                        },
                        {
                            label: `${data.product_name} - Stock Out`,
                            data: data.stock_out,
                            backgroundColor: colors.out,
                            borderColor: colors.out.replace('0.6', '1'),
                            borderWidth: 1,
                            type: 'bar'
                        }
                    ];
                });

                stockChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: dates,
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        },
                        plugins: {
                            zoom: {
                                zoom: {
                                    wheel: {
                                        enabled: true,
                                    },
                                    pinch: {
                                        enabled: true
                                    },
                                    mode: 'x',
                                },
                                pan: {
                                    enabled: true,
                                    mode: 'x',
                                }
                            },
                            title: {
                                display: true,
                                text: 'Monthly Stock Transactions'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.dataset.label}: ${context.formattedValue} units`;
                                    }
                                }
                            },
                            legend: {
                                display: true,
                                position: 'bottom'
                            }
                        },
                        scales: {
                            x: {
                                title: { 
                                    display: true, 
                                    text: 'Month' 
                                }
                            },
                            y: {
                                title: { 
                                    display: true, 
                                    text: 'Stock Quantity' 
                                },
                                beginAtZero: true,
                                
                            }
                        }
                    }
                });

                // Zoom controls
                document.getElementById('zoomIn').addEventListener('click', () => {
                    stockChart.zoom(1.2);
                });

                document.getElementById('zoomOut').addEventListener('click', () => {
                    stockChart.zoom(0.8);
                });

                document.getElementById('resetZoom').addEventListener('click', () => {
                    stockChart.resetZoom();
                });
            }

            // Initial chart rendering
            createChart(rawChartData);

            // Product filter functionality
            productSelect.addEventListener('change', function() {
                const selectedProduct = this.value;
                const filteredData = selectedProduct 
                    ? rawChartData.filter(item => item.product_name === selectedProduct)
                    : rawChartData;
                createChart(filteredData);
            });
        });
    </script>
</body>
</html>