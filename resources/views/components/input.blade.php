@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-lime-400 dark:focus:border-lime-400 focus:ring-indigo-500 dark:focus:ring-lime-400 rounded-md shadow-sm']) !!}>
