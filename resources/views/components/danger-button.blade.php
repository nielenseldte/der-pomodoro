<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-gray-800 text-red-600 inline-flex items-center px-4 py-2 btn dark:bg-red-700 dark:text-gray-900 hover:bg-gray-800 hover:text-red-600 hover:outline-double']) }}>
    {{ $slot }}
</button>
