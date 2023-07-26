<div wire:poll.1000ms>

    <div class="flex flex-col items-center justify-center  bg-gray-800" style="{{ $onbreak ? 'display:none' : ''  }}">
        <div class="rounded-lg border-4 border-lime-400 border-double p-8" style="width: 224px; height: 132px;">
            <div class="text-6xl font-cool text-lime-400" id="timer">{{ $ticker }}</div>
        </div>
    </div>

    <div class="flex flex-col items-center justify-center  bg-gray-800" style="{{ $onbreak ? '' : 'display:none'  }}">
        <div class="rounded-lg border-4 border-blue-400 border-double p-8" style="width: 224px; height: 132px;" >
            <div class="text-6xl font-cool text-blue-400" id="timer">{{ $break }}</div>
        </div>
    </div>
</div>


