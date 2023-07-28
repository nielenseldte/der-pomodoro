<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-lime-400 leading-tight">
            {{ __('About') }}
        </h2>
    </x-slot>

    <main class="dark:text-lime-400">
        <div class="text-center py-7">
            <h1 class="text-4xl">{{ __('About Us') }}</h1>
        </div>

        <section class="dark:text-gray-400 dark:bg-gray-900 body-font">

            <div class="container px-5 py-20 mx-auto flex flex-col">
                <div class="lg:w-4/6 mx-auto">

                    <div class="flex flex-col sm:flex-row mt-10">
                        <div class="sm:w-1/3 text-center sm:pr-8 sm:py-8">
                            <div
                                class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-gray-800 text-gray-600">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" class="w-10 h-10" viewBox="0 0 24 24">
                                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <div class="flex flex-col items-center text-center justify-center">
                                <h2 class="font-medium title-font mt-4 dark:text-white text-lg">Nielen Seldte</h2>
                                <div class="w-12 h-1 bg-lime-400 rounded mt-2 mb-4"></div>
                                <p class="text-base dark:text-gray-400">Computer Science Student at IUBH Germany</p>
                            </div>
                        </div>
                        <div
                            class="sm:w-2/3 sm:pl-8 sm:py-8 sm:border-l border-lime-400 sm:border-t-0 border-t mt-4 pt-4 sm:mt-0 text-center sm:text-left">
                            <p class="leading-relaxed text-lg mb-4">
                            <p class="mb-4">Welcome to my Pomodoro App – your productivity
                                companion! As an aspiring software engineer studying at the International University of
                                Applied Sciences, I have always been an ardent believer in the Pomodoro technique's
                                effectiveness. Whether it's acing exams or completing tasks with utmost efficiency, this
                                technique is always effective.</p>

                            <p class="mb-4">Driven by my passion for simplicity and productivity, I decided to develop
                                my own take
                                on a Pomodoro app. My focus is on providing you with the essentials and removing any
                                distractions. My aim is simple – to help you maximize productivity and achieve your
                                goals, one productive Pomodoro session at a time.</p>

                            <p class="mb-4">I invite you to join me on this journey of staying focused, organized, and
                                achieving
                                your best work. Embrace simplicity, boost your productivity, and experience the power of
                                the Pomodoro technique with my app.</p>

                            <p class="mb-4">Let's make each Pomodoro count!.</p>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-app-layout>
