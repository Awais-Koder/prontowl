@extends('layouts.app')

@push('title')
    <title>Discover - Campaigns</title>
@endpush
@section('style')
    <style>
        .pagination-details {
            display: none;
        }
    </style>
@endsection
@section('slider')
@include('sections.slider')
@endsection
@section('content')
    <div class="p-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6">
            @foreach ($campaigns as $campaign)
                @php
                    $totalDonations = $campaign->donations->sum('amount');
                    $fundingGoal = $campaign->funding_goal;
                    $percentage = $fundingGoal > 0 ? ($totalDonations / $fundingGoal) * 100 : 0;
                @endphp
                <div class="max-w-full bg-white border border-gray-200 rounded-lg shadow">
                    <a
                        href="{{ route('campaign.show', base64_encode($campaign->id)) }}">
                        <img class="rounded-t-lg h-64 object-cover w-full" src="{{ Storage::url($campaign->feature_image) }}"
                            alt="" />
                    </a>
                    <div class="p-5">
                        <p class="text-gray-500 uppercase mb-4 text-sm">
                            {{ $campaign->purpose == 'online_course' ? 'Online Course' : $campaign->purpose }}</p>
                        <a
                            href="{{ route('campaign.show', base64_encode($campaign->id)) }}">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">
                                {{ Str::limit($campaign->title, 20) }}
                            </h5>
                        </a>
                        <p class="mb-3 font-normal text-gray-500 dark:text-gray-400">
                            {!! Str::limit($campaign->description, 100) !!}
                        </p>
                        <p class="text-sm py-3 text-gray-500">
                            {{ $campaign->donations_count }} Donors
                        </p>
                        <div
                            style="height:6px;border-radius: 10px; background: #6EC052; width: {{ min($percentage, 100) }}%; ">
                        </div>
                        <div class="flex justify-between my-2 mt-4">
                            <span>$ {{ $totalDonations }} raised</span>
                            <span class="text-[#6ec052]">{{ number_format($percentage, 2) }}% funded</span>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
        <div class="py-5 flex justify-center pagination">
            {{ $campaigns->links() }}
        </div>
    </div>
@endsection
