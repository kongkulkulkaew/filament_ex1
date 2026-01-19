<div class="space-y-2">
    @foreach($variables as $key => $value)
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-2">
            <div class="text-xs font-semibold text-gray-600 uppercase tracking-wide">{{ $key }}</div>
            <div class="text-sm font-mono text-gray-800 mt-1">{{ $value }}</div>
        </div>
    @endforeach
</div>
