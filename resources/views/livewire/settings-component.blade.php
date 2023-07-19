<div>
    <!-- component -->
    <section class="max-w-4xl p-6 mx-auto bg-lime-400 rounded-md shadow-md dark:bg-gray-800 mt-20">
        <h1 class="text-xl font-bold text-white capitalize dark:text-lime-400">{{ __('Pomodoro settings') }}</h1>
        <form>
            <div class="grid grid-cols-1 gap-6 mt-4">


                <div>
                    <label class="text-lime-400 dark:text-lime-400" for="focuslength">{{ __('Focus Length') }}</label>
                    <input id="focuslength" type="number" min="15" max="50"
                        class="block w-full px-4 py-2 mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-lime-400 dark:focus:border-lime-400 focus:ring-lime-500 dark:focus:ring-lime-400 rounded-md shadow-sm"
                        value={{ $settings['focusLength'] }}>

                </div>

                <div>
                    <label class="text-lime-400 dark:text-lime-400" for="sbreaklength">{{ __('Short Break length') }}</label>
                    <input id="sbreaklength" type="number" min="3" max="15"
                        class="block w-full px-4 py-2 mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-lime-400 dark:focus:border-lime-400 focus:ring-lime-500 dark:focus:ring-lime-400 rounded-md shadow-sm"
                        value={{ $settings['shortBreakLength'] }}>

                </div>

                <div>
                    <label class="text-lime-400 dark:text-lime-400" for="lbreaklength">{{ __('Long Break Length') }}</label>
                    <input id="lbreaklength" type="number" min="10" max="30"
                        class="block w-full px-4 py-2 mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-lime-400 dark:focus:border-lime-400 focus:ring-lime-500 dark:focus:ring-lime-400 rounded-md shadow-sm"
                        value={{ $settings['longBreakLength'] }}>

                </div>

                <div>
                    <label class="text-lime-400 dark:text-lime-400" for="lbreakinterval">{{ __('Long break interval') }}</label>
                    <input id="lbreakinterval" type="number" min="3" max="6"
                        class="block w-full px-4 py-2 mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-lime-400 dark:focus:border-lime-400 focus:ring-lime-500 dark:focus:ring-lime-400 rounded-md shadow-sm"
                        value={{ $settings['longBreakInterval'] }}>

                </div>

                <div>
                    <label class="text-lime-400 dark:text-lime-400" for="dailygoal">{{ __('Daily Goal') }}</label>
                    <input id="dailygoal" type="number" min="1" max="18"
                        class="block w-full px-4 py-2 mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-lime-400 dark:focus:border-lime-400 focus:ring-lime-500 dark:focus:ring-lime-400 rounded-md shadow-sm"
                        value={{ $settings['dailyGoal'] }}>

                </div>



                <div class="flex justify-end mt-6 space-x-4">
                    <x-button>{{ __('Save') }}</x-button>
                    <x-button class="px-6 py-2 ml-2">{{ __('Reset to Defaults') }}</x-button>
                </div>
        </form>
    </section>

</div>
