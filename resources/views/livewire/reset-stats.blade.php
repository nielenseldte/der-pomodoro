<div>
    <x-danger-button wire:click='resetAll'>{{ __('Reset Stats') }}</x-danger-button>
    <div class="mt-4" style="{{ $reset_clicked ? '' : 'display:none' }}">
        <div class="rounded-lg border-2 border-red-600 bg-red-300 p-4">
            <span class="error text-red-600 font-semibold">{{ __('Are you sure you want to reset your stats?') }}</span>
            <div class="mt-4 flex justify-center space-x-4">
                <x-danger-button wire:click='confirm'>{{ __('Yes') }}</x-button>
                    <x-danger-button wire:click='cancel'>{{ __('Cancel') }}</x-button>
            </div>
        </div>
    </div>
</div>
