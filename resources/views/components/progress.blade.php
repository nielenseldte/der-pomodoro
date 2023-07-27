<div class="py-8">
    <h1 class="font-bold">{{ __('Goal:') }} {{ $dailyGoal }} {{ __('hours') }}</h1>
    <div class="w-full bg-gray-200 rounded-full h-4 dark:bg-gray-700"
        style="border: 2px solid rgb(4, 247, 85); box-sizing: content-box;">
        <div class="bg-lime-400 h-4 rounded-full" style="width: {{ $progress }}%; margin-left: 0;"></div>
    </div>
    <h1 class="font-bold">{{ $progress }} {{ __('%') }}</h1>


</div>
