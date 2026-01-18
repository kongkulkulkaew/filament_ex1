<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Form สำหรับป้อนข้อมูล --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📝 ป้อนจำนวนแถวและคอลัมน์</h2>
            
            <form wire:submit.prevent>
                {{ $this->form }}
            </form>
        </div>

        {{-- แสดงตารางสูตรคูณ (Nested Loop) --}}
        @if($rows !== null && $columns !== null && $rows >= 1 && $columns >= 1)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">📊 ตารางสูตรคูณ (ใช้ Nested Loop)</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    ตารางนี้สร้างโดยใช้ <strong>Nested Loop</strong> (Loop ซ้อน Loop):
                    <br>- Outer Loop: วนลูปสำหรับแถว (แม่สูตรคูณ)
                    <br>- Inner Loop: วนลูปสำหรับคอลัมน์ (ตัวคูณ)
                </p>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border border-gray-300 dark:border-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-300 border-r border-gray-300 dark:border-gray-600">
                                    ×
                                </th>
                                @for($col = 1; $col <= $columns; $col++)
                                    <th class="px-3 py-2 text-center text-xs font-bold text-gray-700 dark:text-gray-300 border-r border-gray-300 dark:border-gray-600 bg-blue-50 dark:bg-blue-900/20">
                                        {{ $col }}
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($this->generateTable() as $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-3 text-center text-sm font-bold text-gray-700 dark:text-gray-300 border-r border-gray-300 dark:border-gray-600 bg-blue-50 dark:bg-blue-900/20">
                                        {{ $row['multiplier'] }}
                                    </td>
                                    @foreach($row['cells'] as $cell)
                                        <td class="px-3 py-2 text-center text-sm border-r border-gray-300 dark:border-gray-600
                                            @if($cell['color'] === 'success') bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 font-semibold
                                            @elseif($cell['color'] === 'info') bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 font-semibold
                                            @elseif($cell['color'] === 'warning') bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400
                                            @else bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400
                                            @endif">
                                            {{ $cell['result'] }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-xs text-gray-600 dark:text-gray-400">
                    <strong>สี:</strong>
                    <span class="inline-block w-4 h-4 bg-green-100 dark:bg-green-900/20 border border-green-300 dark:border-green-600 ml-2 mr-1"></span> หาร 10 ลงตัว
                    <span class="inline-block w-4 h-4 bg-blue-100 dark:bg-blue-900/20 border border-blue-300 dark:border-blue-600 ml-4 mr-1"></span> หาร 5 ลงตัว
                    <span class="inline-block w-4 h-4 bg-yellow-100 dark:bg-yellow-900/20 border border-yellow-300 dark:border-yellow-600 ml-4 mr-1"></span> จำนวนคู่
                    <span class="inline-block w-4 h-4 bg-red-100 dark:bg-red-900/20 border border-red-300 dark:border-red-600 ml-4 mr-1"></span> จำนวนคี่
                </div>
            </div>

            {{-- แสดง Pattern โดยใช้ Nested Loop --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">⭐ Pattern 1: สามเหลี่ยมดาว (Nested Loop)</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    สร้างโดยใช้ Nested Loop: Outer Loop สำหรับแถว, Inner Loop สำหรับจำนวน * ในแต่ละแถว
                </p>
                
                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg font-mono text-lg">
                    @foreach($this->generatePattern() as $pattern)
                        <div class="text-blue-600 dark:text-blue-400">
                            {{ $pattern['stars'] }}
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- แสดง Pattern ตัวเลข --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">🔢 Pattern 2: สามเหลี่ยมตัวเลข (Nested Loop)</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    สร้างโดยใช้ Nested Loop: Outer Loop สำหรับแถว, Inner Loop สำหรับตัวเลขในแต่ละแถว
                </p>
                
                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg font-mono text-lg">
                    @foreach($this->generateNumberPattern() as $pattern)
                        <div class="text-purple-600 dark:text-purple-400">
                            @foreach($pattern['numbers'] as $number)
                                {{ $number }}
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- แสดง Pattern พีระมิด --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">🔺 Pattern 3: พีระมิด (Nested Loop)</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    สร้างโดยใช้ Nested Loop: Outer Loop สำหรับแถว, Inner Loop 2 ตัว (spaces และ stars)
                </p>
                
                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg font-mono text-lg">
                    @foreach($this->generatePyramidPattern() as $pattern)
                        <div class="text-green-600 dark:text-green-400">
                            <span>{!! $pattern['spaces'] !!}</span>{{ $pattern['stars'] }}
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- อธิบายหลักการ Nested Loop --}}
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📚 หลักการ Nested Loop (Loop ซ้อน Loop)</h2>
            
            <div class="space-y-4 text-sm">
                <div>
                    <h3 class="font-semibold mb-2">1. โครงสร้าง Nested Loop</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>// Outer Loop (Loop ภายนอก)
for ($row = 1; $row <= 10; $row++) {
    // Inner Loop (Loop ภายใน)
    for ($col = 1; $col <= 10; $col++) {
        // ทำงานในแต่ละเซลล์
        $result = $row * $col;
    }
}</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        <strong>Outer Loop</strong> จะวนรอบก่อน แล้วในแต่ละรอบของ Outer Loop 
                        <strong>Inner Loop</strong> จะวนรอบทั้งหมด
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">2. จำนวนครั้งที่ทำงาน</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>// Outer Loop: 10 รอบ
// Inner Loop: 10 รอบต่อรอบของ Outer Loop
// รวมทั้งหมด: 10 × 10 = 100 ครั้ง</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        ถ้า Outer Loop วน 10 รอบ และ Inner Loop วน 10 รอบ 
                        จะทำงานทั้งหมด <strong>10 × 10 = 100 ครั้ง</strong>
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">3. ตัวอย่างการทำงาน</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>// รอบที่ 1 ของ Outer Loop (row = 1)
    // Inner Loop วน 10 รอบ (col = 1, 2, 3, ..., 10)
    // สร้างแถวที่ 1 ทั้งหมด

// รอบที่ 2 ของ Outer Loop (row = 2)
    // Inner Loop วน 10 รอบอีกครั้ง (col = 1, 2, 3, ..., 10)
    // สร้างแถวที่ 2 ทั้งหมด

// ... และต่อไป</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Outer Loop แต่ละรอบจะสร้าง 1 แถว โดย Inner Loop จะสร้างทุกเซลล์ในแถวนั้น
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">4. Nested Loop หลายชั้น</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>// Loop ชั้นที่ 1
for ($i = 1; $i <= 3; $i++) {
    // Loop ชั้นที่ 2
    for ($j = 1; $j <= 3; $j++) {
        // Loop ชั้นที่ 3
        for ($k = 1; $k <= 3; $k++) {
            // ทำงานทั้งหมด 3 × 3 × 3 = 27 ครั้ง
        }
    }
}</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        สามารถซ้อน Loop ได้หลายชั้น แต่ละชั้นจะเพิ่มจำนวนครั้งที่ทำงานแบบทวีคูณ
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">5. การใช้ If-Else ภายใน Nested Loop</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>for ($row = 1; $row <= 10; $row++) {
    for ($col = 1; $col <= 10; $col++) {
        $result = $row * $col;
        
        // ใช้ if-else เพื่อตัดสินใจ
        if ($result % 2 == 0) {
            // จำนวนคู่
        } else {
            // จำนวนคี่
        }
    }
}</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        สามารถใช้ <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">if-else</code> 
                        ภายใน Nested Loop เพื่อตัดสินใจในแต่ละเซลล์
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
