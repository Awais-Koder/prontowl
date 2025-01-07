<div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @php
        $level = Auth::user()->level->level ?? 0;
    @endphp

    <div class="rounded-lg shadow-md dark:bg-black">
        <div class="flex justify-between items-center border-b pb-4 mb-4">
            <div>
                <span class="text-lg font-semibold">Current Level : </span>
                <span id="current-vcc-level" class="text-blue-600 font-bold">{{ $level }}</span>
            </div>
            <div class="relative">
                @if ($level < 3)
                <x-filament::button wire:click='showModal'>
                    Upgrade to level {{$level + 1}}
                </x-filament::button>
                @else
                    <p>Maximum level reached</p>
                @endif
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <!-- Level 1 -->
            <div class=" p-4 rounded-lg shadow hover:shadow-md transition">
                <span class="text-primary-500 mr-2">
                    <i class="fa fa-star"></i>
                </span>
                <div class="flex items-center mb-4">
                    <div>
                        <h6 class="text-lg font-bold">Level 1</h6>
                        <p class="text-sm text-gray-600">E-learning</p>
                    </div>
                </div>
                <div class="text-sm text-gray-700 mb-4">$500 limit</div>
                <div class="relative h-4 w-full bg-gray-200 rounded-full">
                    <div class="absolute top-0 left-0 h-4 bg-primary-500 rounded-full" style="width: 30%;"></div>
                </div>
                <div class="text-right text-sm text-gray-600 mt-2">30%</div>
            </div>

            <!-- Level 2 -->
            <div class=" p-4 rounded-lg shadow hover:shadow-md transition">
                <span class="text-primary-500">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                </span>
                <div class="flex items-center mb-4">
                    <div>
                        <h6 class="text-lg font-bold">Level 2</h6>
                        <p class="text-sm text-gray-600">Training Fee</p>
                    </div>
                </div>
                <div class="text-sm text-gray-700 mb-4">$1K limit</div>
                <div class="relative h-4 w-full bg-gray-200 rounded-full">
                    <div class="absolute top-0 left-0 h-4 bg-primary-500 rounded-full" style="width: 60%;"></div>
                </div>
                <div class="text-right text-sm text-gray-600 mt-2">60%</div>
            </div>

            <!-- Level 3 -->
            <div class=" p-4 rounded-lg shadow hover:shadow-md transition">
                <span class="text-primary-500">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                </span>
                <div class="flex items-center mb-4">
                    <div>
                        <h6 class="text-lg font-bold">Level 3</h6>
                        <p class="text-sm text-gray-600">Tuition Fee</p>
                    </div>
                </div>
                <div class="text-sm text-gray-700 mb-4">$4K above</div>
                <div class="relative h-4 w-full bg-gray-200 rounded-full">
                    <div class="absolute top-0 left-0 h-4 bg-primary-500 rounded-full" style="width: 90%;"></div>
                </div>
                <div class="text-right text-sm text-gray-600 mt-2">90%</div>
            </div>
            <x-filament::modal id="upgrade-level" :close-by-escaping="false" :close-by-clicking-away="false" width="3xl">
                <x-slot name="heading">
                    KYC for level {{ $level + 1 }}
                </x-slot>
                {{-- from from the livewire component --}}
                {{ $this->form }}
                <x-slot name="footerActions">
                    <x-filament::button wire:click='upgradeLevel' icon="heroicon-o-arrow-long-up" :disabled="!Auth::user()->email_verified_at">
                        Upgrade Level
                    </x-filament::button>
                </x-slot>
            </x-filament::modal>
        </div>
        {{-- <div>
                <p>Your application for upgrading to Level {{$level + 1}} is currently pending.</p>
            </div> --}}
    </div>

</div>
