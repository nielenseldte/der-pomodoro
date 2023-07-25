<div wire:poll.1000ms>
<button wire:click="stopStart" class="btn outline-double dark:bg-lime-400 dark:text-gray-800 hover:bg-gray-800 hover:text-lime-400 hover:outline-double">
             {{ $button_text }}
</button>
@if (!$user_break)
<button wire:click="cancel" class="btn outline-double dark:bg-lime-400 dark:text-gray-800 hover:bg-gray-800 hover:text-lime-400 hover:outline-double">
             {{ __('Cancel') }}
</button>
@elseif ($user_break)
<button wire:click="skipBreak" class="btn dark:bg-lime-400 dark:text-gray-800 hover:bg-gray-800 hover:text-lime-400 hover:outline-double">
             {{ __('Skip') }}
</button>

@endif


</div>
