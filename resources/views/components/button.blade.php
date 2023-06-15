<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gray-800 text-lime-400 inline-flex items-center px-4 py-2 btn dark:bg-lime-400 dark:text-gray-800 hover:bg-gray-800 hover:text-lime-400 hover:outline-double']) }}>
    {{ $slot }}
</button>
