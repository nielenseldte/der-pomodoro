<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite('resources/css/app.css')
        <title>Der Pomodoro</title>
    </head>

    <main class = "flex flex-col xl:flex-row h-screen">

    <div><img src="" alt=""></div>

    <body class="flex justify-center absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2  bg-white dark:bg-gray-800">
  <div class=" bg-white text-black flex items-center max-w-md p-6 rounded-md sm:p-10 dark:bg-gray-800 dark:text-lime-400 shadow-x1">
    <div class="m-8 text-center">
      <h1 class="my-3 text-4xl font-bold">Welcome to Der Pomodoro</h1>
      <p class="text-sm dark:text-gray-400">How would you like to proceed?</p>
      <br>
      <button type="button" class="btn mb-4 md:mb-0 outline dark:bg-lime-400 dark:text-gray-800 hover:bg-gray-800 hover:text-lime-400 hover:outline-double">
        <a href="/login">Login</a>
      </button>
      <button type="button" class="btn outline dark:bg-lime-400 dark:text-gray-800 hover:bg-gray-800 hover:text-lime-400 hover:outline-double">
        <a href="/register">Register</a>
      </button>
    </div>
  </div>
</body>
    </main>
</html>
