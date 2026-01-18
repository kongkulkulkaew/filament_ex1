<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Form สำหรับป้อนข้อมูล --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📝 ป้อนข้อมูล</h2>
            
            <form wire:submit.prevent>
                {{ $this->form }}
            </form>
        </div>

        {{-- แสดงผลการคำนวณ BMI --}}
        @if($weight !== null && $height !== null && $height > 0)
            @php
                $bmi = $this->calculateBMI();
                $category = $this->getBMICategory();
            @endphp

            @if($bmi !== null)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4">📊 ผลการคำนวณ BMI</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- แสดงน้ำหนัก --}}
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800 rounded-lg p-6 border border-blue-200 dark:border-gray-600">
                            <div class="text-center">
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">น้ำหนัก</div>
                                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                    {{ number_format($weight, 1) }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">กิโลกรัม</div>
                            </div>
                        </div>

                        {{-- แสดงส่วนสูง --}}
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-gray-700 dark:to-gray-800 rounded-lg p-6 border border-purple-200 dark:border-gray-600">
                            <div class="text-center">
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">ส่วนสูง</div>
                                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                                    {{ number_format($height, 1) }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">เซนติเมตร</div>
                            </div>
                        </div>

                        {{-- แสดงค่า BMI --}}
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-800 rounded-lg p-6 border border-green-200 dark:border-gray-600">
                            <div class="text-center">
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">ค่า BMI</div>
                                <div class="text-4xl font-bold text-green-600 dark:text-green-400">
                                    {{ $bmi }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">kg/m²</div>
                            </div>
                        </div>
                    </div>

                    {{-- แสดงหมวดหมู่ --}}
                    <div class="mt-6 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-600">
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                {{ $category['category'] }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                {{ $category['description'] }}
                            </div>
                            @if($category['recommendation'])
                                <div class="text-sm text-blue-600 dark:text-blue-400 font-medium">
                                    💡 {{ $category['recommendation'] }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endif

        {{-- แสดงมาตรฐาน BMI --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📋 มาตรฐาน BMI</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                ช่วงค่า BMI
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                หมวดหมู่
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                สถานะ
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($this->getBMIScale() as $scale)
                            @php
                                $bmi = $this->calculateBMI();
                                $isCurrent = $bmi !== null && 
                                    $bmi >= $scale['min'] && 
                                    $bmi < $scale['max'];
                            @endphp
                            <tr class="{{ $isCurrent ? 'bg-blue-50 dark:bg-blue-900/20' : '' }} hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    {{ $scale['min'] }} - {{ $scale['max'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $scale['category'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($isCurrent)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            ค่าปัจจุบัน
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

        {{-- แสดงตาราง BMI ตัวอย่าง (ใช้ for loop) --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📊 ตาราง BMI ตัวอย่าง (ใช้ For Loop)</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                ตารางนี้สร้างโดยใช้ <strong>for loop</strong> เพื่อคำนวณ BMI จากน้ำหนักและส่วนสูงที่แตกต่างกัน
            </p>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                ส่วนสูง (cm)
                            </th>
                            @foreach($this->generateBMITable()[0]['weights'] as $weight)
                                <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                    {{ $weight['weight'] }}kg
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($this->generateBMITable() as $row)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100">
                                    {{ $row['height'] }} cm
                                </td>
                                @foreach($row['weights'] as $weight)
                                    <td class="px-2 py-2 text-center">
                                        <div class="text-xs font-semibold 
                                            @if($weight['color'] === 'success') text-green-600 dark:text-green-400
                                            @elseif($weight['color'] === 'warning') text-yellow-600 dark:text-yellow-400
                                            @else text-red-600 dark:text-red-400
                                            @endif">
                                            {{ $weight['bmi'] }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $weight['category'] }}
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- อธิบายหลักการ --}}
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📚 หลักการทำงาน (If-Else และ For Loop)</h2>
            
            <div class="space-y-4 text-sm">
                <div>
                    <h3 class="font-semibold mb-2">1. การใช้ If-Else เพื่อตัดเกณฑ์</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>if ($bmi < 18.5) {
    return 'น้ำหนักน้อย';
} elseif ($bmi >= 18.5 && $bmi < 23) {
    return 'ปกติ';
} elseif ($bmi >= 23 && $bmi < 25) {
    return 'ท้วม';
} else {
    return 'อ้วน';
}</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        ใช้ <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">if-else</code> 
                        เพื่อตรวจสอบช่วงค่า BMI และส่งคืนหมวดหมู่ที่เหมาะสม
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">2. การใช้ For Loop เพื่อสร้างตาราง</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>// วนลูปสำหรับส่วนสูง
for ($height = 150; $height <= 190; $height += 10) {
    // วนลูปสำหรับน้ำหนัก
    for ($weight = 40; $weight <= 120; $weight += 10) {
        // คำนวณ BMI
        $bmi = $weight / ($height * $height);
    }
}</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        ใช้ <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">for loop</code> 
                        เพื่อสร้างข้อมูลหลายรายการ โดยวนลูปสำหรับส่วนสูงและน้ำหนักที่แตกต่างกัน
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">3. Nested Loop (Loop ซ้อน Loop)</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>for ($height = 150; $height <= 190; $height += 10) {
    for ($weight = 40; $weight <= 120; $weight += 10) {
        // คำนวณ BMI สำหรับแต่ละคู่ของ height และ weight
    }
}</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        ใช้ <strong>nested loop</strong> (loop ซ้อน loop) เพื่อสร้างตาราง 2 มิติ
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">4. การใช้ If-Else ภายใน Loop</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>for ($i = 0; $i < 10; $i++) {
    if ($i % 2 == 0) {
        // จำนวนคู่
    } else {
        // จำนวนคี่
    }
}</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        สามารถใช้ <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">if-else</code> 
                        ภายใน <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">for loop</code> 
                        เพื่อตัดสินใจในแต่ละรอบ
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
