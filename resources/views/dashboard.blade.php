<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-lime-400 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
  <main class="dark:text-lime-400">
  <div class="flex flex-wrap -m-4 my-20 px-4 md:px-10">
    <div class="w-full md:w-1/2 lg:w-1/4 p-4">
      <div class=" bg-white dark:bg-gray-800 bg-opacity-40 p-6 rounded-lg">
        <div class="text-center">
          <h1 class="text-2xl underline italic">{{ __('Start Now') }}</h1>
        </div>
        <div class="py-8 flex flex-col justify-center items-center">
          @livewire('timer-component') <br>

          <button type="button" class="btn outline-double dark:bg-lime-400 dark:text-gray-800 hover:bg-gray-800 hover:text-lime-400 hover:outline-double">
            <a href="/timer">{{ __('Go To Timer') }}</a>
          </button>
        </div>
      </div>
    </div>
    <div class="w-full md:w-1/2 lg:w-1/4 p-4">
      <div class="bg-white dark:bg-gray-800 bg-opacity-40 p-6 rounded-lg">
        <div class="text-center">
          <h1 class="text-2xl italic font-serif">"{{ __($quote['text']) }}"</h1>
          <p class="text-lg font-semibold mt-2">~{{ __($quote['author']) }}</p>
        </div>
      </div>
    </div>
    <div class="w-full md:w-1/2 lg:w-1/4 p-4">
      <div class="bg-white dark:bg-gray-800 bg-opacity-40 p-6 rounded-lg">
        <div class="text-center">
          <h1 class="text-2xl underline italic">{{ __('Goal Progress') }}</h1>
        </div>
        <x-progress/>
      </div>
    </div>
    <div class="w-full md:w-1/2 lg:w-1/4 p-4">
      <div class="bg-white dark:bg-gray-800 bg-opacity-40 p-6 rounded-lg">
        <div class="text-center">
          <h1 class="text-2xl underline italic">{{ __('Productivity Score') }}</h1>
        </div>
        <div class="py-8">
          <h1 class="font-bold">{{ __('Your score:') }}</h1>
        </div>
      </div>
    </div>
  </div>
</main>

</x-app-layout>
