<div>
    <div class="flex justify-start space-x-2">
        <x-filament::button color="green" href="javascript:;" tag="a" icon="heroicon-o-arrow-long-right">
            Convert
        </x-filament::button>
        <div class="px-1"></div>
        <x-filament::modal width="md">
            <x-slot name="trigger">
                <x-filament::button color="green" icon="heroicon-o-plus">
                    Add Balance
                </x-filament::button>
            </x-slot>
            <x-slot name="heading">
                Add Balance
            </x-slot>
            <x-filament::input.wrapper suffix-icon="heroicon-o-currency-dollar" suffix-icon-color="warning"
                :valid="!$errors->has('amount')">
                <x-filament::input type="number" wire:model="amount" placeholder="Enter amount" min="1" />
            </x-filament::input.wrapper>
            @error('amount')
                <span class="text-danger-500 text-sm">{{ $message }}</span>
            @enderror
            <x-slot name="footer">
                <x-filament::button wire:click='makePayment' icon="heroicon-o-currency-dollar">
                    Pay
                </x-filament::button>
            </x-slot>

        </x-filament::modal>
    </div>

</div>
