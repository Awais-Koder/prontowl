@extends('layouts.app')

@push('title')
    <title>Pronwtowl | Make Donation</title>
@endpush
@section('content')
    @include('modals.share-modal')
    <h1 class="my-10 text-center text-3xl text-gray-600 font-bold">Make a Donation</h1>
    <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 lg:py-10 px-3 md:px-16 lg:px-20">
        <div class="max-w-full bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('campaign.make.donation', base64_encode($campaign->id)) }}" method="POST"
                class="space-y-6">
                @csrf
                <!-- Amount -->
                <div>
                    <label for="amount" class="text-sm font-medium text-gray-700">Amount</label> <sup
                        class="text-red-800 font-extrabold">*</sup>
                    <input type="number" step="1" name="amount" id="amount"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-custom-green focus:border-custom-green p-2"
                        placeholder="Enter the amount ($)">
                    @error('amount')
                        <span class="text-red-700">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Donor Name -->
                <div>
                    <label for="donor_name" class="block text-sm font-medium text-gray-700">Donor Name</label>
                    <input type="text" name="donor_name" id="donor_name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-custom-green focus:border-custom-green p-2"
                        placeholder="Enter donor name">
                </div>

                <!-- Donor Email -->
                <div>
                    <label for="donor_email" class="block text-sm font-medium text-gray-700">Donor Email</label>
                    <input type="email" name="donor_email" id="donor_email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-custom-green focus:border-custom-green p-2"
                        placeholder="Enter donor email">
                </div>

                <!-- Message -->
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                    <textarea name="message" id="message" rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-custom-green focus:border-custom-green p-2"
                        placeholder="Write a message..."></textarea>
                </div>

                <!-- Tip Percentage -->
                <div id="tip_percentage_container" class="hidden">
                    <label for="tip_percentage" class="text-sm font-medium text-gray-700">Tip Percentage</label> <sup
                        class="text-red-700 font-extrabold hidden percentage-required">*</sup>
                    <select name="tip_percentage" id="tip_percentage"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-custom-green focus:border-custom-green p-2">
                        <option value="" disabled selected>-- select percentage --</option>
                        <option value="5">5%</option>
                        <option value="10">10%</option>
                        <option value="15">15%</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                @error('tip_percentage')
                <span class="text-red-700">{{ $message }}</span>
            @enderror
                <!-- Anonymous -->
                <div class="flex items-center">
                    <input type="checkbox" name="anonymous" id="anonymous" value="1"
                        class="h-4 w-4 text-custom-green focus:ring-custom-green border-gray-300 rounded">
                    <label for="anonymous" class="ml-2 block text-sm text-gray-700">Donate anonymously</label>
                </div>

                <!-- Opt-Out of Tip -->
                <div class="flex items-center">
                    <input type="checkbox" name="opt_out_tip" id="opt_out_tip" value="1"
                        class="h-4 w-4 text-custom-green focus:ring-custom-green border-gray-300 rounded">
                    <label for="opt_out_tip" class="ml-2 block text-sm text-gray-700">Opt-out of tip</label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full px-4 py-2 text-white bg-custom-green hover:bg-green-700 rounded-md shadow">
                        Submit
                    </button>
                </div>
            </form>
        </div>
        <div class="flex justify-end">
            @php
                $totalDonations = $campaign->donations->sum('amount');
                $fundingGoal = $campaign->funding_goal;
                $percentage = $fundingGoal > 0 ? ($totalDonations / $fundingGoal) * 100 : 0;
            @endphp
            <div class="min-w-[300px] bg-white border border-gray-200 rounded-lg shadow max-h-[335px]">
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

                    <div class="flex justify-between py-4 px-2 my-2 mt-4 w-full border border-custom-green rounded-md items-center cursor-pointer"
                        id="share-btn">
                        <div class="share-icons">
                            <i class="fa-brands fa-square-facebook text-2xl text-[#3B5998]"></i>
                            <i class="fa-brands fa-square-whatsapp text-2xl text-[#4FCE5D]"></i>
                            <i class="fa-brands fa-square-x-twitter text-2xl text-black"></i>
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
        $(document).ready(function() {
            $('#opt_out_tip').click(function() {
                if ($(this).is(':checked')) {
                    $('#tip_percentage_container').removeClass('hidden');
                } else {
                    $('#tip_percentage_container').addClass('hidden');
                }
            });
            $('#tip_percentage').change(function() {
                if ($(this).val() === 'other') {
                    $('#tip_percentage').replaceWith(
                        '<input type="number" name="tip_percentage" id="tip_percentage" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-custom-green focus:border-custom-green p-2" placeholder="Enter tip percentage"> @error('tip_percentage') {{ $message }} @enderror'
                    );

                    $('.percentage-required').removeClass('hidden');
                } else {
                    $('.percentage-required').addClass('hidden');
                }
            });
        });
    </script>
@endsection
