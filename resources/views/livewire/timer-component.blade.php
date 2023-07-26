<div wire:poll.1000ms>

    <div class="flex flex-col items-center justify-center  dark:bg-gray-800" style="{{ $onbreak ? 'display:none' : ''  }}">
        <div class="rounded-lg border-4 border-black dark:border-lime-400 border-double p-8" style="width: 224px; height: 132px;">
            <div class="text-6xl font-cool dark:text-lime-400" id="timer">{{ $ticker }}</div>
        </div>
         <h1 class="dark:text-lime-400 text-2xl font-bold mt-4 text-center">Focus</h1>
    </div>


    <div class="flex flex-col items-center justify-center  dark:bg-gray-800" style="{{ $onbreak ? '' : 'display:none'  }}">
        <div class="rounded-lg border-4 border-black dark:border-blue-400 border-double p-8" style="width: 224px; height: 132px;" >
            <div class="text-6xl font-cool dark:text-blue-400" id="timer">{{ $break }}</div>

        </div>
        <h1 class="dark:text-blue-400 text-2xl font-bold mt-4 text-center">Take a break!</h1>
    </div>

</div>


