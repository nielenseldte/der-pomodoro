<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-lime-400 leading-tight">
            {{ __('Timer') }}
        </h2>
    </x-slot>
<main class="text-lime-400">
  <div class="flex flex-wrap justify-center items-center -m-4 my-20 px-4 md:px-10">
    <div class="w-full md:w-1/2 lg:w-1/4 p-4 ">
      <div class="bg-gray-800 bg-opacity-40 p-6 rounded-lg">
        <div class="text-center">
          <h1 class="text-2xl underline italic">{{ __('Pomodoro Settings') }}</h1>

        </div>
        <div class="py-8 flex flex-col justify-center items-center">
          <form></form>
        </div>
      </div>
    </div>
    <div class="w-full md:w-1/2 lg:w-1/2 p-4 ">
      <div class="bg-gray-800 bg-opacity-40 p-6 rounded-lg">
        <div class="py-8 flex flex-col justify-center items-center">
          @livewire('timer-component') <br>
          <button type="button" class="btn dark:bg-lime-400 dark:text-gray-800 hover:bg-gray-800 hover:text-lime-400 hover:outline-double">
           {{ __('START') }}
          </button><br>
          <button type="button" class="btn dark:bg-lime-400 dark:text-gray-800 hover:bg-gray-800 hover:text-lime-400 hover:outline-double">
            {{ __('Play/Pause') }}
          </button><br>
          <button type="button" class="btn dark:bg-lime-400 dark:text-gray-800 hover:bg-gray-800 hover:text-lime-400 hover:outline-double">
            {{ __('Cancel Session') }}
          </button><br>
          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                $(document).ready(function() {
                // Add click event handler to all buttons
                $('.btn').on('click', function() {
                // Remove the hover class when a button is clicked on mobile devices
                if (window.innerWidth <= 768) {
                 $(this).removeClass('hover:bg-gray-800 hover:text-lime-400');
                         }
                     });
                  });
            </script>
        </div>
      </div>
    </div>
  </div>
</main>
</x-app-layout>
