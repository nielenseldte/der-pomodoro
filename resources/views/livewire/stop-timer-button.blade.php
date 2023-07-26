<div wire:poll.1000ms class="flex justify-end mt-6 space-x-4">
<button wire:click="stopStart" class="btn outline-double dark:bg-lime-400 dark:text-gray-800 hover:bg-gray-800 hover:text-lime-400 hover:outline-double" >
             {{ $button_text }}
</button>
@if (!$user?->isOnBreak())
<button wire:click="cancel" class="btn outline-double dark:bg-lime-400 dark:text-gray-800 hover:bg-gray-800 hover:text-lime-400 hover:outline-double">
             {{ __('Cancel') }}
</button>
@elseif ($user?->isOnBreak())
<button wire:click="skipBreak" class="btn dark:bg-lime-400 dark:text-gray-800 hover:bg-gray-800 hover:text-lime-400 hover:outline-double">
             {{ __('Skip') }}
</button>

@endif


</div>








