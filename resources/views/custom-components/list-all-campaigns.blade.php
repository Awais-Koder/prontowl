<div class="space-y-4">
    @foreach ($data as $record)
        <div class="p-4 bg-white rounded-lg shadow">
            <h3 class="text-lg font-semibold">{{ $record->title }}</h3>
            <p class="text-sm text-gray-500">{{ $record->purpose }}</p>
        </div>
    @endforeach
</div>
