<x-filament-panels::page>
    <div class="space-y-6">
        {{-- แสดงข้อมูลปัจจุบัน --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📊 ข้อมูลปัจจุบัน (Current Values)</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <strong>ชื่อ:</strong> 
                    <span class="text-gray-600 dark:text-gray-400">{{ $name ?? 'ยังไม่กรอก' }}</span>
                </div>
                <div>
                    <strong>อีเมล:</strong> 
                    <span class="text-gray-600 dark:text-gray-400">{{ $email ?? 'ยังไม่กรอก' }}</span>
                </div>
                <div>
                    <strong>เบอร์โทร:</strong> 
                    <span class="text-gray-600 dark:text-gray-400">{{ $phone ?? 'ยังไม่กรอก' }}</span>
                </div>
                <div>
                    <strong>ประเทศ:</strong> 
                    <span class="text-gray-600 dark:text-gray-400">{{ $country ?? 'ยังไม่เลือก' }}</span>
                </div>
                <div>
                    <strong>อายุ:</strong> 
                    <span class="text-gray-600 dark:text-gray-400">{{ $age ?? 'ยังไม่เลือก' }}</span>
                </div>
                <div>
                    <strong>เปิดใช้งาน:</strong> 
                    <span class="text-gray-600 dark:text-gray-400">{{ $is_active ? 'ใช่' : 'ไม่' }}</span>
                </div>
                <div>
                    <strong>ยอมรับข้อกำหนด:</strong> 
                    <span class="text-gray-600 dark:text-gray-400">{{ $agree_terms ? 'ใช่' : 'ไม่' }}</span>
                </div>
                <div>
                    <strong>เพศ:</strong> 
                    <span class="text-gray-600 dark:text-gray-400">
                        @if($gender === 'male') ชาย
                        @elseif($gender === 'female') หญิง
                        @elseif($gender === 'other') อื่นๆ
                        @else ยังไม่เลือก
                        @endif
                    </span>
                </div>
                <div>
                    <strong>วันเกิด:</strong> 
                    <span class="text-gray-600 dark:text-gray-400">{{ $birth_date ?? 'ยังไม่เลือก' }}</span>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📝 Form Components</h2>
            
            {{-- ใช้ wire:key เพื่อให้ Livewire track component --}}
            <form wire:submit="submit">
                {{ $this->form }}

                <div class="flex gap-4 mt-6">
                    <x-filament::button type="submit">
                        บันทึกข้อมูล
                    </x-filament::button>
                    
                    <x-filament::button 
                        type="button" 
                        color="gray"
                        wire:click="resetForm">
                        รีเซ็ตฟอร์ม
                    </x-filament::button>
                </div>
            </form>
        </div>

        {{-- อธิบายหลักการ --}}
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📚 หลักการรับส่งค่าใน Filament</h2>
            
            <div class="space-y-4 text-sm">
                <div>
                    <h3 class="font-semibold mb-2">1. การประกาศตัวแปร (Properties)</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>public ?string $name = null;
public bool $is_active = false;</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        ประกาศ properties ใน class เพื่อเก็บค่าจาก form
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">2. การ Bind Component กับ Property</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>TextInput::make('name')
    ->label('ชื่อ')
    ->required()</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        ใช้ <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">->make('property_name')</code> 
                        เพื่อ bind component กับ property
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">3. การรับค่า (Two-Way Binding)</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>// เมื่อผู้ใช้กรอกข้อมูล
// ค่าจะถูกส่งไปยัง $this->name อัตโนมัติ

// เมื่อต้องการอ่านค่า
$name = $this->name;</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Filament ใช้ Livewire ทำให้ค่าถูก sync อัตโนมัติ (Two-way binding)
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">4. การส่งค่า (Submit)</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>public function submit(): void
{
    // อ่านค่าจาก properties
    $name = $this->name;
    $email = $this->email;
    
    // หรือใช้ getState()
    $data = $this->form->getState();
}</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        เมื่อกด submit ค่าจะถูกส่งมาที่ method นี้
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">5. Real-time Updates (Live)</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto"><code>TextInput::make('name')
    ->live()
    ->afterStateUpdated(function ($state) {
        // เมื่อค่าเปลี่ยน จะเรียก function นี้ทันที
    })</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        ใช้ <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">->live()</code> 
                        เพื่ออัปเดตแบบ real-time
                    </p>
                </div>
            </div>
        </div>

        {{-- ตารางสรุป Components --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📋 สรุป Form Components</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Component</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ประเภทข้อมูล</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">การใช้งาน</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr>
                            <td class="px-4 py-3 text-sm"><code>TextInput</code></td>
                            <td class="px-4 py-3 text-sm">string</td>
                            <td class="px-4 py-3 text-sm">รับข้อความสั้นๆ</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm"><code>Textarea</code></td>
                            <td class="px-4 py-3 text-sm">string</td>
                            <td class="px-4 py-3 text-sm">รับข้อความหลายบรรทัด</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm"><code>Select</code></td>
                            <td class="px-4 py-3 text-sm">string | int</td>
                            <td class="px-4 py-3 text-sm">Dropdown list</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm"><code>Toggle</code></td>
                            <td class="px-4 py-3 text-sm">bool</td>
                            <td class="px-4 py-3 text-sm">สวิตช์เปิด/ปิด</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm"><code>Checkbox</code></td>
                            <td class="px-4 py-3 text-sm">bool</td>
                            <td class="px-4 py-3 text-sm">ช่องทำเครื่องหมาย</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm"><code>Radio</code></td>
                            <td class="px-4 py-3 text-sm">string</td>
                            <td class="px-4 py-3 text-sm">ปุ่มตัวเลือกเดียว</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm"><code>DatePicker</code></td>
                            <td class="px-4 py-3 text-sm">string (date)</td>
                            <td class="px-4 py-3 text-sm">เลือกวันที่</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm"><code>FileUpload</code></td>
                            <td class="px-4 py-3 text-sm">array</td>
                            <td class="px-4 py-3 text-sm">อัปโหลดไฟล์</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
