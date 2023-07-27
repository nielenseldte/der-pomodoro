<div>
    <div class="py-8">
        <h1 class="font-bold">{{ __('Sessions started:') }}   <span class="text-blue-400">{{ $sessionsStarted }}</span></h1><BR>
        <h1 class="font-bold">{{ __('Sessions finished:') }}  <span class="text-blue-400">{{ $sessionsFinished }}</span></h1><BR>
        <h1 class="font-bold">{{ __('Hours focused:') }}  <span class="text-blue-400">{{ $hoursFocused }}</span></h1><BR>
        <h1 class="font-bold">{{ __('Your productivity score:') }}  <span class="text-blue-400">{{ $productivityScore }}</span></h1><BR>
    </div>
    <div class="text-center">

        <x-danger-button>{{ __('RESET STATS') }}</x-danger-button>

    </div>
</div>
