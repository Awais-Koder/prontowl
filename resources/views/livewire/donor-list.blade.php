<div>
    <h1 class="text-custom-green text-2xl font-extrabold my-10">Donors</h1>
    <div id="donors">
        @forelse ($donations as $donation)
            <div class="border grid-cols-2 flex justify-between p-5 mb-2">
                <div class="flex">
                    <img src="{{ asset('images/default-theme-photo.svg') }}" alt="" class="rounded-full h-10 w-10">
                    <div class="ml-3">
                        @if($donation->anonymous)
                        <h1 class="font-extrabold">Anonymous</h1>
                        @else
                        <h1 class="font-extrabold">{{ $donation->donor_name }}</h1>
                        @endif
                        <p class="text-gray-600">Donated On {{ $donation->created_at->format('M d, Y') }}</p>
                        <p class="text-black mt-2">{{ $donation->message }}</p>
                    </div>
                </div>
                <div>
                    <h1>${{ $donation->amount }}</h1>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-600">No donors found.</p>
        @endforelse
    </div>

    @if ($donations->hasMorePages())
        <div class="flex justify-center my-14">
            <button wire:click="loadMore" class="text-custom-green">
                See All Donors ({{ $donations->total() - $donations->count() }})
            </button>
        </div>
    @endif
</div>
