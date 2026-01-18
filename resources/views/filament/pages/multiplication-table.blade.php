<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Form สำหรับป้อนแม่สูตรคูณ --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📝 ป้อนแม่สูตรคูณ</h2>
            
            <form wire:submit.prevent>
                {{ $this->form }}
            </form>
        </div>

        {{-- แสดงผลแม่สูตรคูณ --}}
        @if($multiplier && $multiplier >= 1)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">
                    แม่สูตรคูณ {{ $multiplier }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($this->getMultiplicationResults() as $item)
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800 rounded-lg p-4 border border-blue-200 dark:border-gray-600 hover:shadow-md transition-shadow">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 mb-2">
                                    {{ $item['formula'] }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $multiplier }} × {{ $item['number'] }} = {{ $item['result'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- แสดงผลแบบตาราง --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">📊 แสดงผลแบบตาราง</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    ลำดับ
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    สูตร
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    ผลลัพธ์
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($this->getMultiplicationResults() as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $item['number'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ $multiplier }} × {{ $item['number'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600 dark:text-blue-400">
                                        {{ $item['result'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-yellow-800 dark:text-yellow-200">
                        กรุณาป้อนแม่สูตรคูณที่ต้องการ (1-100)
                    </p>
                </div>
            </div>
        @endif

        {{-- อธิบายหลักการ --}}
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📚 หลักการทำงาน</h2>
            
            <div class="space-y-4 text-sm">
                <div>
                    <h3 class="font-semibold mb-2">1. การรับค่าจากผู้ใช้</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>TextInput::make('multiplier')
    ->label('แม่สูตรคูณ')
    ->numeric()
    ->live()</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        เมื่อผู้ใช้ป้อนค่า ค่าจะถูกส่งไปยัง <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">$this->multiplier</code> อัตโนมัติ
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">2. การคำนวณผลลัพธ์</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>public function getMultiplicationResults(): array
{
    $results = [];
    for ($i = 1; $i <= 12; $i++) {
        $results[] = [
            'number' => $i,
            'result' => $this->multiplier * $i,
            'formula' => "{$this->multiplier} × {$i} = " . ($this->multiplier * $i),
        ];
    }
    return $results;
}</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        คำนวณผลลัพธ์ตั้งแต่ 1 ถึง 12 และส่งคืนเป็น array
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">3. การแสดงผล</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>@foreach($this->getMultiplicationResults() as $item)
    &lt;div&gt;{{ $item['formula'] }}&lt;/div&gt;
@endforeach</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        ใช้ Blade template เพื่อวนลูปแสดงผลลัพธ์
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">4. Real-time Updates</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>->live()</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        เมื่อใช้ <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">->live()</code> 
                        ผลลัพธ์จะอัปเดตทันทีเมื่อผู้ใช้เปลี่ยนค่า
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
