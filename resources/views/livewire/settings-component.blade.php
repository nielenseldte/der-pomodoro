<div>
    <!-- component -->
    <section class="max-w-4xl p-6 mx-auto bg-lime-400 rounded-md shadow-md dark:bg-gray-800 mt-20">
        <h1 class="text-xl font-bold text-white capitalize dark:text-lime-400">{{ __('Pomodoro settings') }}</h1>
        <form wire:submit.prevent="save">
            @csrf
            <div class="grid grid-cols-1 gap-6 mt-4">
                <div>
                    @if (session()->has('message'))
                        <div class="rounded-lg bg-green-500 p-4">
                            <p class="text-white font-bold">{{ session('message') }}</p>
                        </div>
                    @endif
                </div>


                <div>
                    <label class="text-lime-400 dark:text-lime-400" for="focuslength">{{ __('Focus Length') }}</label>
                    <input id="focusLength" name="focusLength" type="number"
                        class="block w-full px-4 py-2 mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-lime-400 dark:focus:border-lime-400 focus:ring-lime-500 dark:focus:ring-lime-400 rounded-md shadow-sm"
                        wire:model="settings.session_length">

                    @if ($errors->has('settings.session_length'))
                        <div class="rounded-lg border-2 border-red-600 bg-red-300 p-2">
                            <span
                                class="error text-red-600 font-semibold">{{ $errors->first('settings.session_length') }}</span>
                        </div>
                    @endif

                </div>

                <div>
                    <label class="text-lime-400 dark:text-lime-400"
                        for="shortBreakLength">{{ __('Short Break length') }}</label>
                    <input id="shortBreakLength" name="shortBreakLength" type="number"
                        class="block w-full px-4 py-2 mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-lime-400 dark:focus:border-lime-400 focus:ring-lime-500 dark:focus:ring-lime-400 rounded-md shadow-sm"
                        wire:model="settings.short_break_length">
                    @if ($errors->has('settings.short_break_length'))
                        <div class="rounded-lg border-2 border-red-600 bg-red-300 p-2">
                            <span
                                class="error text-red-600 font-semibold">{{ $errors->first('settings.short_break_length') }}</span>
                        </div>
                    @endif

                </div>
                <div>
                    <label class="text-lime-400 dark:text-lime-400"
                        for="longBreakLength">{{ __('Long Break Length') }}</label>
                    <input id="longBreakLength" name="longBreakLength" type="number"
                        class="block w-full px-4 py-2 mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-lime-400 dark:focus:border-lime-400 focus:ring-lime-500 dark:focus:ring-lime-400 rounded-md shadow-sm"
                        wire:model="settings.long_break_length">

                     @if ($errors->has('settings.long_break_length'))
                        <div class="rounded-lg border-2 border-red-600 bg-red-300 p-2">
                            <span
                                class="error text-red-600 font-semibold">{{ $errors->first('settings.long_break_length') }}</span>
                        </div>
                    @endif

                </div>

                <div>
                    <label class="text-lime-400 dark:text-lime-400"
                        for="longBreakInterval">{{ __('Long break interval') }}</label>
                    <input id="longBreakInterval" name="longBreakInterval" type="number"
                        class="block w-full px-4 py-2 mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-lime-400 dark:focus:border-lime-400 focus:ring-lime-500 dark:focus:ring-lime-400 rounded-md shadow-sm"
                        wire:model="settings.long_break_interval">

                   @if ($errors->has('settings.long_break_interval'))
                        <div class="rounded-lg border-2 border-red-600 bg-red-300 p-2">
                            <span
                                class="error text-red-600 font-semibold">{{ $errors->first('settings.long_break_interval') }}</span>
                        </div>
                    @endif

                </div>

                <div>
                    <label class="text-lime-400 dark:text-lime-400" for="dailyGoal">{{ __('Daily Goal') }}</label>
                    <input id="dailyGoal" name="dailyGoal" type="number"
                        class="block w-full px-4 py-2 mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-lime-400 dark:focus:border-lime-400 focus:ring-lime-500 dark:focus:ring-lime-400 rounded-md shadow-sm"
                        wire:model="settings.daily_goal">
                    @if ($errors->has('settings.daily_goal'))
                        <div class="rounded-lg border-2 border-red-600 bg-red-300 p-2">
                            <span
                                class="error text-red-600 font-semibold">{{ $errors->first('settings.daily_goal') }}</span>
                        </div>
                    @endif

                </div>


                <div class="flex justify-end mt-6 space-x-4">
                    <x-button type="submit">{{ __('Save') }}</x-button>
                    <x-button wire:click="resetToDefault" class="px-6 py-2 ml-2">{{ __('Reset to Defaults') }}</x-button>
                </div>
        </form>
    </section>
    @livewireScripts
</div>
