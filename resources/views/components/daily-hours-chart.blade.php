<div>
    <div class="text-center">
        <h1 class="text-2xl underline italic">{{ __('Hours by day') }}</h1>
        <div>
            <canvas id="hoursByDay"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            //Fetches the hoursbyday element in the DOM
            const myChartDailyHours = document.getElementById('hoursByDay');
            //creates a new chart based on the passed settings
            new Chart(myChartDailyHours, @json($settings));
        </script>
    </div>
</div>
