<div>
    <div class="text-center">
        <h1 class="text-2xl underline italic">{{ __('Hours by day') }}</h1>
        <div>
            <canvas id="hoursByDay"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const myChartDailyHours = document.getElementById('hoursByDay');
            new Chart(myChartDailyHours, @json($settings));
        </script>
    </div>
</div>
