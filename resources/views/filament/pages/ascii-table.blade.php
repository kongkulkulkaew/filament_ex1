<x-filament-panels::page>

    <div class="space-y-6">
        {{-- Form สำหรับป้อนข้อมูล --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📝 ป้อนช่วงรหัส ASCII</h2>
            
            <form wire:submit.prevent>
                {{ $this->form }}
            </form>
                  
          
        </div>

        {{-- แสดงตาราง ASCII --}}
        @if($startCode !== null && $endCode !== null && $columnsPerRow !== null && 
            $startCode >= 0 && $endCode <= 255 && $startCode <= $endCode && $columnsPerRow >= 1)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">📊 ตารางรหัส ASCII (ใช้ Nested Loop)</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    ตารางนี้สร้างโดยใช้ <strong>Nested Loop</strong>:
                    <br>- Outer Loop: วนลูปสำหรับรหัส ASCII ทั้งหมด ({{ $startCode }}-{{ $endCode }})
                    <br>- Inner Logic: แบ่งเป็นแถวตามจำนวนคอลัมน์ ({{ $columnsPerRow }} คอลัมน์ต่อแถว)
                    <br>- ใช้ <strong>if-else</strong> เพื่อตรวจสอบประเภทตัวอักษรและกำหนดสี
                </p>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border border-gray-300 dark:border-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 border-r border-gray-300 dark:border-gray-600">
                                    รหัส<br>ASCII
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-300 border-r border-gray-300 dark:border-gray-600">
                                    ตัวอักษร
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-300 border-r border-gray-300 dark:border-gray-600">
                                    Hex
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300">
                                    ประเภท
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($this->generateASCIITable() as $row)
                                @foreach($row as $cell)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100 border-r border-gray-300 dark:border-gray-600">
                                            {{ $cell['code'] }}
                                        </td>
                                        <td class="px-4 py-3 text-center text-2xl font-bold border-r border-gray-300 dark:border-gray-600
                                            @if($cell['color'] === 'success') text-green-600 dark:text-green-400
                                            @elseif($cell['color'] === 'info') text-blue-600 dark:text-blue-400
                                            @elseif($cell['color'] === 'warning') text-yellow-600 dark:text-yellow-400
                                            @else text-red-600 dark:text-red-400
                                            @endif">
                                            {{ $cell['char'] }}
                                        </td>
                                        <td class="px-4 py-3 text-center text-sm font-mono text-gray-600 dark:text-gray-400 border-r border-gray-300 dark:border-gray-600">
                                            0x{{ str_pad($cell['hex'], 2, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $cell['type'] }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-xs text-gray-600 dark:text-gray-400">
                    <strong>สี:</strong>
                    <span class="inline-block w-4 h-4 bg-green-100 dark:bg-green-900/20 border border-green-300 dark:border-green-600 ml-2 mr-1"></span> ตัวอักษร (A-Z, a-z)
                    <span class="inline-block w-4 h-4 bg-blue-100 dark:bg-blue-900/20 border border-blue-300 dark:border-blue-600 ml-4 mr-1"></span> ตัวเลข (0-9)
                    <span class="inline-block w-4 h-4 bg-yellow-100 dark:bg-yellow-900/20 border border-yellow-300 dark:border-yellow-600 ml-4 mr-1"></span> เครื่องหมาย
                    <span class="inline-block w-4 h-4 bg-red-100 dark:bg-red-900/20 border border-red-300 dark:border-red-600 ml-4 mr-1"></span> Control Characters
                </div>
            </div>

            {{-- แสดงตารางแบบ Grid (Nested Loop) --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">🔢 ตาราง ASCII แบบ Grid (Nested Loop)</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    แสดงผลแบบ Grid โดยใช้ Nested Loop: Outer Loop สำหรับแถว, Inner Loop สำหรับคอลัมน์
                </p>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border border-gray-300 dark:border-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-2 py-2 text-center text-xs font-bold text-gray-700 dark:text-gray-300 border-r border-gray-300 dark:border-gray-600">
                                    \
                                </th>
                                @for($col = 0; $col < $columnsPerRow; $col++)
                                    <th class="px-2 py-2 text-center text-xs font-bold text-gray-700 dark:text-gray-300 border-r border-gray-300 dark:border-gray-600 bg-blue-50 dark:bg-blue-900/20">
                                        +{{ $col }}
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @php
                                $rows = $this->generateASCIITable();
                                $rowIndex = 0;
                            @endphp
                            @for($base = $startCode; $base <= $endCode; $base += $columnsPerRow)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-2 py-2 text-center text-xs font-bold text-gray-700 dark:text-gray-300 border-r border-gray-300 dark:border-gray-600 bg-blue-50 dark:bg-blue-900/20">
                                        {{ $base }}
                                    </td>
                                    @for($col = 0; $col < $columnsPerRow; $col++)
                                        @php
                                            $code = $base + $col;
                                            if ($code > $endCode) {
                                                $cell = null;
                                            } else {
                                                $cell = $code >= 32 && $code <= 126 ? [
                                                    'code' => $code,
                                                    'char' => chr($code),
                                                    'hex' => strtoupper(dechex($code)),
                                                ] : [
                                                    'code' => $code,
                                                    'char' => 'N/A',
                                                    'hex' => strtoupper(dechex($code)),
                                                ];
                                            }
                                        @endphp
                                        @if($cell)
                                            <td class="px-2 py-2 text-center border-r border-gray-300 dark:border-gray-600">
                                                <div class="text-xs font-mono text-gray-600 dark:text-gray-400">
                                                    {{ $cell['code'] }}
                                                </div>
                                                <div class="text-lg font-bold 
                                                    @if($code >= 65 && $code <= 90) text-green-600 dark:text-green-400
                                                    @elseif($code >= 97 && $code <= 122) text-green-600 dark:text-green-400
                                                    @elseif($code >= 48 && $code <= 57) text-blue-600 dark:text-blue-400
                                                    @elseif($code >= 32 && $code <= 126) text-yellow-600 dark:text-yellow-400
                                                    @else text-red-600 dark:text-red-400
                                                    @endif">
                                                    {{ $cell['char'] }}
                                                </div>
                                                <div class="text-xs font-mono text-gray-500 dark:text-gray-500">
                                                    0x{{ str_pad($cell['hex'], 2, '0', STR_PAD_LEFT) }}
                                                </div>
                                            </td>
                                        @else
                                            <td class="px-2 py-2 border-r border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900">
                                                <!-- Empty cell -->
                                            </td>
                                        @endif
                                    @endfor
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- แสดงหมวดหมู่ ASCII --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">📋 หมวดหมู่ ASCII</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($this->getASCIICategories() as $category)
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                {{ $category['name'] }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                <strong>ช่วง:</strong> {{ $category['range'] }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $category['description'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- อธิบายหลักการ Nested Loop และ If-Else --}}
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📚 หลักการ Nested Loop และ If-Else ในโปรแกรมนี้</h2>
            
            <div class="space-y-4 text-sm">
                <div>
                    <h3 class="font-semibold mb-2">1. Nested Loop สำหรับสร้างตาราง</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>// Outer Loop: วนลูปสำหรับรหัส ASCII ทั้งหมด
for ($code = 32; $code <= 126; $code++) {
    // Inner Logic: แบ่งเป็นแถวตามจำนวนคอลัมน์
    $currentRow[] = [
        'code' => $code,
        'char' => chr($code),
    ];
    
    // ใช้ if เพื่อตรวจสอบว่าครบจำนวนคอลัมน์แล้วหรือยัง
    if (count($currentRow) >= $columnsPerRow) {
        $table[] = $currentRow;
        $currentRow = [];
    }
}</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        ใช้ <strong>for loop</strong> เพื่อวนลูปรหัส ASCII และใช้ <strong>if</strong> เพื่อแบ่งเป็นแถว
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">2. If-Else เพื่อตรวจสอบประเภทตัวอักษร</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>// ใช้ if-else เพื่อตรวจสอบประเภท
if ($code >= 65 && $code <= 90) {
    $type = 'ตัวพิมพ์ใหญ่';
    $color = 'success';
} elseif ($code >= 97 && $code <= 122) {
    $type = 'ตัวพิมพ์เล็ก';
    $color = 'success';
} elseif ($code >= 48 && $code <= 57) {
    $type = 'ตัวเลข';
    $color = 'info';
} else {
    $type = 'เครื่องหมาย';
    $color = 'warning';
}</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        ใช้ <strong>if-else</strong> เพื่อตรวจสอบช่วงรหัส ASCII และกำหนดประเภทและสี
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">3. Nested Loop สำหรับสร้างตาราง Grid</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>// Outer Loop: วนลูปสำหรับแถว (เพิ่มทีละ columnsPerRow)
for ($base = 32; $base <= 126; $base += 8) {
    // Inner Loop: วนลูปสำหรับคอลัมน์
    for ($col = 0; $col < 8; $col++) {
        $code = $base + $col;
        // แสดงข้อมูลแต่ละเซลล์
    }
}</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        ใช้ <strong>Nested Loop</strong> เพื่อสร้างตาราง Grid แบบ 2 มิติ
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">4. If-Else ภายใน Loop</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>for ($code = 0; $code <= 255; $code++) {
    // ใช้ if เพื่อตรวจสอบว่าสามารถแสดงตัวอักษรได้หรือไม่
    if ($code >= 32 && $code <= 126) {
        $displayChar = chr($code);
    } else {
        $displayChar = 'N/A'; // Control characters
    }
}</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        ใช้ <strong>if-else</strong> ภายใน <strong>for loop</strong> เพื่อตัดสินใจในแต่ละรอบ
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
