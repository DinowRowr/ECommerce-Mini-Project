<!DOCTYPE html>
<html lang="en">

<head>
    <title>Analytics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            position: static;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px;
            background: black;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1;
        }

        h1 {
            font-size: 1.5em;
            margin: 0;
        }

        h2 {
            padding-left: 20px;
        }

        #navigation {
            margin-top: 10px;
            margin-right: 30px;
            padding: 10px;
        }

        #navigation a {
            position: relative;
            text-decoration: none;
            color: white;
            font-weight: 500;
            margin-right: 20px;
        }

        #navigation a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 100%;
            height: 2px;
            background: red;
            transform-origin: right;
            transform: scaleX(0);
            transition: transform .3s;
        }

        #navigation a:hover::after {
            transform-origin: left;
            transform: scaleX(1);
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>PEP - SQUAD APPARELS (ADMIN)</h1>
        <div id="navigation">
            <a href="adminIndex.php" class="logout">HOME</a>
        </div>
    </div>
    <h2>Item Sales</h2>
    <div style="width: 800px; height: 400px;">
        <canvas id="salesChart"></canvas>
    </div>

    <h2>Sales Overview</h2>
    <div style="width: 800px; height: 400px;">
        <canvas id="overviewChart"></canvas>
    </div>

    <h2>Total Sales</h2>
    <div style="width: 800px; height: 400px;">
        <canvas id="totalSalesChart"></canvas>
    </div>

    <script>
        // Function to fetch chart data from fetchChartData.php
        async function fetchChartData() {
            try {
                const response = await fetch('fetchChartData.php');
                const data = await response.json();
                return data;
            } catch (error) {
                console.log(error);
                return [];
            }
        }

        // Function to initialize and render the item sales chart
        async function renderSalesChart() {
            const chartData = await fetchChartData();

            const itemIds = chartData.item_data.map(item => item.item_id);
            const quantities = chartData.item_data.map(item => item.quantity_sold);

            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: itemIds,
                    datasets: [{
                        label: 'Quantity Sold',
                        data: quantities,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    indexAxis: 'y', // Display item IDs on the y-axis
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Quantity Sold'
                            },
                            ticks: {
                                beginAtZero: true
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Item ID'
                            }
                        }
                    }
                }
            });
        }

        // Function to initialize and render the sales overview chart
        async function renderOverviewChart() {
            const chartData = await fetchChartData();

            const dailySales = chartData.sales_data.daily;
            const weeklySales = chartData.sales_data.weekly;
            const monthlySales = chartData.sales_data.monthly;
            const yearlySales = chartData.sales_data.yearly;

            const dates = Object.keys(dailySales);
            const dailyValues = Object.values(dailySales);
            const weeklyValues = Object.values(weeklySales);
            const monthlyValues = Object.values(monthlySales);
            const yearlyValues = Object.values(yearlySales);

            const ctx = document.getElementById('overviewChart').getContext('2d');
            const overviewChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Daily Sales',
                        data: dailyValues,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        fill: false
                    }, {
                        label: 'Weekly Sales',
                        data: weeklyValues,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        fill: false
                    }, {
                        label: 'Monthly Sales',
                        data: monthlyValues,
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: false
                    }, {
                        label: 'Yearly Sales',
                        data: yearlyValues,
                        backgroundColor: 'rgba(255, 206, 86, 0.7)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Sales Count'
                            },
                            ticks: {
                                beginAtZero: true
                            }
                        }
                    }
                }
            });
        }

        // Function to initialize and render the total sales chart
        async function renderTotalSalesChart() {
            const chartData = await fetchChartData();

            const totalSales = chartData.total_sales;
            const months = totalSales.map(sale => sale.month); // Updated key: 'month' instead of 'day'
            const revenues = totalSales.map(sale => sale.revenue);

            const ctx = document.getElementById('totalSalesChart').getContext('2d');
            const totalSalesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months, // Updated key: 'months' instead of 'days'
                    datasets: [{
                        label: 'Total Revenue',
                        data: revenues,
                        backgroundColor: 'rgba(153, 102, 255, 0.7)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Month' // Updated label to 'Month'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Revenue'
                            },
                            ticks: {
                                beginAtZero: true
                            }
                        }
                    }
                }
            });
        }

        // Render the charts on page load
        document.addEventListener('DOMContentLoaded', () => {
            renderSalesChart();
            renderOverviewChart();
            renderTotalSalesChart();
        });
    </script>
</body>

</html>
