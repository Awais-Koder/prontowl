@extends('layouts.app')

@push('title')
    <title>{{ $campaign->title }}</title>
@endpush
@section('style')
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            width: 0;
        }

        .scrollbar-hide::-webkit-scrollbar-track {
            background-color: transparent;
        }

        .scrollbar-hide::-webkit-scrollbar-thumb {
            background-color: transparent;
        }
    </style>
@endsection
@section('content')
    @include('modals.share-modal')
    <div class="flex justify-center px-24">
        <h1 class="text-center sm:text-xs md:text-xl font-extrabold mt-6 text-gray-600">{{ $campaign->title }}</h1>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 lg:py-10 px-3 md:px-16 lg:px-20 max-h-96">
        <div class="my-9">
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab"
                    data-tabs-toggle="#default-tab-content" role="tablist">
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-custom-green rounded-t-lg text-custom-green font-extrabold"
                            id="story-tab" data-tabs-target="#story" type="button" role="tab" aria-controls="story"
                            aria-selected="false">Story</button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button
                            class="inline-block p-4 border-custom-green rounded-t-lg hover:text-gray-600 hover:border-gray-300 text-custom-green font-extrabold"
                            id="dashboard-tab" data-tabs-target="#dashboard" type="button" role="tab"
                            aria-controls="dashboard" aria-selected="false">Donors</button>
                    </li>
                </ul>
            </div>
            @php
                $gallaryImages = json_decode($campaign->gallary_images, true);
            @endphp
            <div id="default-tab-content">
                <div class="hidden pt-2 rounded-lg" id="story" role="tabpanel" aria-labelledby="story-tab">
                    <div id="default-carousel" class="relative w-full" data-carousel="slide">
                        <!-- Carousel wrapper -->
                        <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
                            <!-- Item 1 -->
                            @forelse ($gallaryImages as $image)
                                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                    <img src="{{ Storage::url($image) }}"
                                        class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                                        alt="Gallary Image" style="object-fit: cover">
                                </div>
                            @empty
                                <p class="text-center text-gray-600">Images not found.</p>
                            @endforelse
                            <!-- Item 2 -->
                        </div>
                        <!-- Slider indicators -->
                        <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="true" aria-label="Slide 1"
                                data-carousel-slide-to="0"></button>
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 2"
                                data-carousel-slide-to="1"></button>
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 3"
                                data-carousel-slide-to="2"></button>
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 4"
                                data-carousel-slide-to="3"></button>
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 5"
                                data-carousel-slide-to="4"></button>
                        </div>
                        <!-- Slider controls -->
                        <button type="button"
                            class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                            data-carousel-prev>
                            <span
                                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M5 1 1 5l4 4" />
                                </svg>
                                <span class="sr-only">Previous</span>
                            </span>
                        </button>
                        <button type="button"
                            class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                            data-carousel-next>
                            <span
                                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                                <span class="sr-only">Next</span>
                            </span>
                        </button>
                    </div>
                    <div>
                        <h1 class="text-custom-green text-2xl font-extrabold mt-10">Campaign Story</h1>
                        <h3 class="text-gray-600 font-bold mt-3">{{ $campaign->title }}</h3>

                        <div id="description-content">
                            <p class="text-gray-600 mt-3 campaign-description">
                                {!! Str::limit($campaign->description, 100) !!}
                                <span id="full-description" class="hidden">{!! Str::substr($campaign->description, 100) !!}</span>
                            </p>
                        </div>
                        <div class="flex justify-center items-center">
                            <button id="toggle-description" class="text-custom-green mt-1 mr-1">View More <i
                                    class="fa-solid fa-chevron-down"></i></button>
                        </div>
                    </div>
                    <div>
                        <livewire:donor-list :campaignId="$campaign->id" />
                    </div>
                </div>
                <div class="hidden p-4 rounded-lg max-h-[600px] overflow-y-auto scrollbar-hide" id="dashboard"
                    role="tabpanel" aria-labelledby="dashboard-tab">
                    <livewire:donor-list :campaignId="$campaign->id" />

                </div>
            </div>
        </div>
        <div class="flex justify-end">
            @php
                $totalDonations = $campaign->donations->sum('amount');
                $fundingGoal = $campaign->funding_goal;
                $percentage = $fundingGoal > 0 ? ($totalDonations / $fundingGoal) * 100 : 0;
            @endphp
            <div class="min-w-[300px] bg-white border border-gray-200 rounded-lg shadow max-h-[440px]">
                <div class="p-5">
                    <h1 class="text-4xl text-custom-green max-w-lg text-center font-extrabold">
                        ${{ number_format($campaign->donations->sum('amount')) }}</h1>
                    <p class="text-center text-gray-500">raised of ${{ number_format($campaign->funding_goal) }} goal</p>
                    <div class="w-full bg-custom-green h-2 rounded mt-2" style="width: {{ min($percentage, 100) }}%; ">
                    </div>
                    <div class="flex justify-between my-2 mt-4">
                        <span class="text-custom-green">{{ number_format($percentage, 2) }}% funded</span>
                        <span class="text-gray-600">{{ $campaign->donations_count }} Donors</span>
                    </div>
                    {{-- calculation for time --}}
                    @php
                        use Carbon\Carbon;

                        // Current time
                        $now = Carbon::now();

                        // Campaign start and end times
                        $starting_date = $campaign->starting_date; // Example starting date
                        $ending_date = $campaign->ending_date; // Example ending date

                        // Parse the dates with Carbon
                        $start = Carbon::parse($starting_date);
                        $end = Carbon::parse($ending_date);

                        // Check if the campaign is still ongoing
                        if ($now->lt($end)) {
                            // If current time is less than ending date
                            $difference = $now->diff($end); // Time remaining
                        } else {
                            $difference = null; // Campaign has ended
                        }
                    @endphp

                    <div class="flex justify-between my-2 mt-4 w-full border bg-[#F5F6F7] p-2 rounded-md">
                        <div class="flex justify-center items-center w-1/3">
                            <span class="lg:text-4xl text-gray-500 text-xl md:text-2xl">
                                <i class="fa-regular fa-clock"></i>
                            </span>
                        </div>
                        <div class="mt-1 text-gray-500">
                            @if ($difference)
                                <span class="text-xs">Time left</span>
                                <p class="text-xl font-extrabold">
                                    {{ $difference->d }} <sub>d</sub>
                                    {{ $difference->h }} <sub>h</sub>
                                    {{ $difference->i }} <sub>m</sub>
                                </p>
                            @else
                                <span class="text-xs">Campaign ended</span>
                                <p class="text-xl font-extrabold text-red-500">
                                    Expired
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-between my-2 mt-4 w-full">
                        <a href="{{ route('campaign.donate.now', base64_encode($campaign->id)) }}"
                            class="text-white hover:bg-green-700 focus:outline-none focus:ring-4 font-medium rounded-md h-16 text-sm text-center dark:bg-custom-green dark:focus:ring-0 uppercase flex justify-center items-center w-full m-0">Donate
                            now</a>
                    </div>
                    <div class="flex justify-between py-4 px-2 my-2 mt-4 w-full border border-custom-green rounded-md items-center cursor-pointer"
                        id="share-btn">
                        <div class="share-icons">
                            <i class="fa-brands fa-square-facebook text-2xl text-[#3B5998]"></i>
                            <i class="fa-brands fa-square-whatsapp text-2xl text-[#4FCE5D]"></i>
                            <i class="fa-brands fa-square-x-twitter text-2xl text-[#000000]"></i>
                        </div>
                        <div class="share-text">
                            <p class="text-custom-green font-semibold">Share with friends</p>
                        </div>
                    </div>
                    <button class="hidden" data-modal-target="default-modal" data-modal-toggle="default-modal"
                        id="show-modal-btn"></button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shareBtn = document.getElementById('share-btn');
            const showModalBtn = document.getElementById('show-modal-btn');

            if (shareBtn && showModalBtn) {
                shareBtn.addEventListener('click', function() {
                    showModalBtn.click();
                });
            } else {
                console.error('One or both elements not found: share-btn, show-modal-btn');
            }
        });
        // get the remaining donors
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('toggle-description');
            const fullDescription = document.getElementById('full-description');

            toggleButton.addEventListener('click', function() {
                if (fullDescription.classList.contains('hidden')) {
                    fullDescription.classList.remove('hidden');
                    toggleButton.innerHTML = 'View Less <i class="fa-solid fa-chevron-up"></i>';
                    toggleButton.classList.add('mt-3'); // Add margin top using Tailwind
                } else {
                    fullDescription.classList.add('hidden');
                    toggleButton.innerHTML = 'View More <i class="fa-solid fa-chevron-down"></i>';
                    toggleButton.classList.remove('mt-3'); // Remove margin top using Tailwind
                }
            });
        });
    </script>
@endsection
