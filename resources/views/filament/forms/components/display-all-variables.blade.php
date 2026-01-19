<div class="rounded-lg border-2 border-blue-200 bg-blue-50 p-4">
    <div class="text-sm font-semibold text-blue-900 mb-3 uppercase tracking-wide">ค่าตัวแปรทั้งหมดใน Form</div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @foreach($all_data as $key => $value)
            <div class="rounded border border-blue-200 bg-white p-2">
                <div class="text-xs font-semibold text-blue-700 uppercase tracking-wide mb-1">{{ $key }}</div>
                <div class="text-sm font-mono text-gray-800 break-words">{{ $value }}</div>
            </div>
        @endforeach
    </div>
</div>
