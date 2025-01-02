<x-filament-widgets::widget>
    <x-filament::section>
        @php
            if (Auth::check() && Auth::user()->hasRole('Admin')) {
                $campaigns = \App\Models\Campaign::with('donations')->latest()->limit(2)->get();
            } else {
                $campaigns = \App\Models\Campaign::with('donations')
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->limit(2)
                    ->get();
            }
        @endphp
        <style>
            .fi-section-content {
                padding: 0px;
            }
        </style>
        <div class="grid grid-cols-1 md:grid-cols-1 gap-1 p-2">
            <div class="px-4 py-2 w-full flex justify-between">
                <h2 class="text-2xl font-extrabold">Your Fundraising</h2>
                <a href="{{ route('filament.admin.resources.campaigns.index') }}">&nearr;</a>
            </div>
            @foreach ($campaigns as $campaign)
                @php
                    $totalDonations = $campaign->donations->sum('amount');
                    $fundingGoal = $campaign->funding_goal;
                    $percentage = $fundingGoal > 0 ? ($totalDonations / $fundingGoal) * 100 : 0;
                @endphp
                <div class="dark:bg-black dark:text-white shadow dark:shadow-white rounded-lg px-4 py-3 sm:bg-white sm:w-full w-full grid grid-cols-1">
                    <div class="flex justify-start flex-col">
                        <div class="flex justify-start">
                            <div>
                                <img src="{{ Storage::url($campaign->feature_image) }}" alt="{{ $campaign->title }}"
                                    class="object-cover rounded-lg"
                                    style="height: 40px !important;width:40px;margin-right: 16px">
                            </div>
                            <div>
                                <h2 class="text-sm font-bold">{{ $campaign->title }}</h2>
                                <p class="uppercase" style="text-transform: uppercase;font-size: 12px">
                                    {{ $campaign->purpose }}
                                </p>
                            </div>
                        </div>
                        <div style="height:6px;border-radius: 10px; width: {{ min($percentage, 100) }}%; background:#FBBF24"
                            class="mt-3 mb-2 fundraising-outer-div relative">
                            {{-- <div class="fundraising-inner-div absolute w-full" style="height:16px;border-radius: 10px; background:white;width: 100%;border:1px solid red;">

                            </div> --}}
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm">$ {{ $totalDonations }} / {{$fundingGoal}} </span>
                            <span class="text-sm">{{ number_format($percentage, 2) }}% funded</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
