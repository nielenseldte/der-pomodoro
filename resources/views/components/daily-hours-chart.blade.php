<div>
    <div class="text-center">
        <h1 class="text-2xl underline italic">{{ __('Hours by day') }}</h1>
        <div>
            <canvas id="hoursByDay"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>

            const daysOfTheWeek = @json($daysOfTheWeek);
            const hoursByDay = @json($hoursByDay);
            const data = Array.from({ length: 7 }, (_, day) => hoursByDay.find(item => item.day_of_week === day)?.total_hours || 0);
            const myChartDailyHours = document.getElementById('hoursByDay');

            new Chart(myChartDailyHours, {
                type: 'bar',
                data: {
                    labels: daysOfTheWeek,
                    datasets: [{
                        label: 'Number of Hours per day',
                        data: data,
                        backgroundColor: 'lime',
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                font: {
                                    size: 14, // Set the font size of the label
                                    weight: 'bold' // Set the font weight of the label
                                },
                                color: 'black' // Set the font color of the label
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                               display: false
                            },
                            ticks: {
                                color: 'lime',
                                font: {
                                    weight: 'bold'
                                }
                            }

                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: 'lime',
                                font: {
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });
        </script>
    </div>
</div>
