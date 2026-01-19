<div class="fi-section rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-gray-950 dark:text-white">
            เลือกห้องเรียน
        </h3>
        
        @if($classrooms->isEmpty())
            <p class="text-gray-500 dark:text-gray-400">ไม่มีห้องเรียนที่ใช้งานอยู่</p>
        @else
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($classrooms as $classroom)
                    <a 
                        href="{{ \App\Filament\Resources\TimeLogs\TimeLogResource::getUrl('index', ['classroom_id' => $classroom->id]) }}"
                        class="fi-btn fi-btn-color-gray fi-btn-size-md fi-btn-variant-outline flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-semibold transition duration-75 hover:bg-gray-50 dark:hover:bg-gray-800 {{ $selectedClassroomId === $classroom->id ? 'bg-primary-50 text-primary-600 ring-2 ring-primary-600 dark:bg-primary-500/10 dark:text-primary-400 dark:ring-primary-400' : '' }}"
                    >
                        <span>{{ $classroom->name }}</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">({{ $classroom->code }})</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
