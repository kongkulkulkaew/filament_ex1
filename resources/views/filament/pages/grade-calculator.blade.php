<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Form สำหรับป้อนคะแนนดิบ --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📝 ป้อนคะแนนดิบ</h2>
            
            <form wire:submit.prevent>
                {{ $this->form }}
            </form>
        </div>

        {{-- แสดงผลเกรด --}}
        @if($score !== null && $score >= 0 && $score <= 100)
            @php
                $gradeInfo = $this->calculateGrade();
            @endphp

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">📊 ผลการตัดเกรด</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- แสดงคะแนน --}}
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800 rounded-lg p-6 border border-blue-200 dark:border-gray-600">
                        <div class="text-center">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">คะแนนดิบ</div>
                            <div class="text-4xl font-bold text-blue-600 dark:text-blue-400">
                                {{ number_format($score, 2) }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">คะแนน</div>
                        </div>
                    </div>

                    {{-- แสดงเกรด --}}
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-800 rounded-lg p-6 border border-green-200 dark:border-gray-600">
                        <div class="text-center">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">เกรด</div>
                            <div class="text-5xl font-bold text-green-600 dark:text-green-400 mb-2">
                                {{ $gradeInfo['grade'] }}
                            </div>
                            <div class="text-lg font-semibold text-gray-700 dark:text-gray-300">
                                {{ $gradeInfo['description'] }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- แสดงช่วงคะแนน --}}
                <div class="mt-6 bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400 text-center">
                        <span class="font-semibold">ช่วงคะแนน:</span>
                        {{ $gradeInfo['min_score'] }} - {{ $gradeInfo['max_score'] }} คะแนน
                    </div>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-yellow-800 dark:text-yellow-200">
                        กรุณาป้อนคะแนนดิบที่ถูกต้อง (0-100)
                    </p>
                </div>
            </div>
        @endif

        {{-- แสดงมาตรฐานการตัดเกรด --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📋 มาตรฐานการตัดเกรด</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                เกรด
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                ช่วงคะแนน
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                คำอธิบาย
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                สถานะ
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($this->getGradeScale() as $scale)
                            @php
                                $isCurrentGrade = $score !== null && 
                                    $score >= $scale['min'] && 
                                    $score <= $scale['max'];
                            @endphp
                            <tr class="{{ $isCurrentGrade ? 'bg-blue-50 dark:bg-blue-900/20' : '' }} hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-2xl font-bold 
                                        @if($scale['color'] === 'success') text-green-600 dark:text-green-400
                                        @elseif($scale['color'] === 'info') text-blue-600 dark:text-blue-400
                                        @elseif($scale['color'] === 'warning') text-yellow-600 dark:text-yellow-400
                                        @else text-red-600 dark:text-red-400
                                        @endif">
                                        {{ $scale['grade'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    {{ $scale['min'] }} - {{ $scale['max'] }} คะแนน
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    {{ $scale['description'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($isCurrentGrade)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            คะแนนปัจจุบัน
                                        </span>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-600">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- อธิบายหลักการ --}}
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📚 หลักการทำงาน</h2>
            
            <div class="space-y-4 text-sm">
                <div>
                    <h3 class="font-semibold mb-2">1. การรับค่าจากผู้ใช้</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>TextInput::make('score')
    ->label('คะแนนดิบ')
    ->numeric()
    ->minValue(0)
    ->maxValue(100)
    ->live()</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        เมื่อผู้ใช้ป้อนค่า ค่าจะถูกส่งไปยัง <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">$this->score</code> อัตโนมัติ
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">2. การตัดเกรด</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>public function calculateGrade(): array
{
    if ($score >= 80) return ['grade' => 'A', ...];
    elseif ($score >= 75) return ['grade' => 'B+', ...];
    // ... และอื่นๆ
}</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        ใช้ if-else เพื่อตรวจสอบช่วงคะแนนและส่งคืนเกรดที่เหมาะสม
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">3. การแสดงผล</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>@php
    $gradeInfo = $this->calculateGrade();
@endphp
&lt;div&gt;{{ $gradeInfo['grade'] }}&lt;/div&gt;</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        เรียกใช้ method เพื่อคำนวณเกรดและแสดงผล
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">4. Real-time Updates</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>->live()</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        เมื่อใช้ <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">->live()</code> 
                        เกรดจะอัปเดตทันทีเมื่อผู้ใช้เปลี่ยนคะแนน
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
