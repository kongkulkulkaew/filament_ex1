<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                📋 ใบรับสมัครงาน
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                ตัวอย่าง Form Components ครบถ้วนใน Filament 4.5
            </p>
        </div>

        {{-- Form 1: ข้อมูลส่วนตัว --}}
        <x-filament::section>
            <x-slot name="heading">
                📝 Form 1: ข้อมูลส่วนตัว (Personal Information)
            </x-slot>
            
            <x-slot name="description">
                <strong>Components ที่ใช้:</strong> TextInput, DatePicker, Select, Textarea, Radio, Toggle, Checkbox
                <br><strong>หลักการ:</strong> ค่าจะถูกส่งเมื่อกดปุ่ม Submit
            </x-slot>

            <form wire:submit="submitPersonalInfo">
                {{ $this->getPersonalInfoSchema() }}

                <div class="flex gap-4 mt-6">
                    <x-filament::button type="submit" size="lg">
                        💾 บันทึกข้อมูลส่วนตัว
                    </x-filament::button>
                    
                    <x-filament::button 
                        type="button" 
                        color="gray"
                        size="lg"
                        wire:click="resetForm">
                        🔄 รีเซ็ต Form
                    </x-filament::button>
                </div>
            </form>

            {{-- แสดงข้อมูลปัจจุบัน --}}
            <x-filament::section class="mt-6">
                <x-slot name="heading">
                    📊 ข้อมูลปัจจุบัน (Form 1)
                </x-slot>
                
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ชื่อ-นามสกุล</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">
                            {{ ($first_name ?? '') . ' ' . ($last_name ?? '') ?: 'ยังไม่กรอก' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">อีเมล</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $email ?? 'ยังไม่กรอก' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">เบอร์โทร</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $phone ?? 'ยังไม่กรอก' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">วันเกิด</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $birth_date ?? 'ยังไม่เลือก' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">เพศ</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">
                            @if($gender === 'male') ชาย
                            @elseif($gender === 'female') หญิง
                            @elseif($gender === 'other') อื่นๆ
                            @else ยังไม่เลือก
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ตำแหน่งที่สมัคร</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $position_applied ?? 'ยังไม่เลือก' }}</dd>
                    </div>
                </dl>
            </x-filament::section>
        </x-filament::section>

        {{-- Form 2: ข้อมูลเพิ่มเติม --}}
        <x-filament::section>
            <x-slot name="heading">
                📄 Form 2: ข้อมูลเพิ่มเติม (Additional Information)
            </x-slot>
            
            <x-slot name="description">
                <strong>Components ที่ใช้:</strong> Textarea, TextInput, FileUpload, Select (Multiple), Toggle
                <br><strong>หลักการ:</strong> ค่าจะถูกส่งเมื่อกดปุ่ม Submit
            </x-slot>

            <form wire:submit="submitAdditionalInfo">
                {{ $this->getAdditionalInfoSchema() }}

                <div class="flex gap-4 mt-6">
                    <x-filament::button type="submit" size="lg">
                        💾 บันทึกข้อมูลเพิ่มเติม
                    </x-filament::button>
                </div>
            </form>

            {{-- แสดงข้อมูลปัจจุบัน --}}
            <x-filament::section class="mt-6">
                <x-slot name="heading">
                    📊 ข้อมูลปัจจุบัน (Form 2)
                </x-slot>
                
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ปีที่ทำงาน</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $years_of_experience ?? '0' }} ปี</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">เงินเดือนเดิม</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">฿{{ number_format($previous_salary ?? 0, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">วันทำงานที่ต้องการ</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">
                            @if($preferred_work_days)
                                {{ implode(', ', array_map(fn($day) => [
                                    'monday' => 'จันทร์',
                                    'tuesday' => 'อังคาร',
                                    'wednesday' => 'พุธ',
                                    'thursday' => 'พฤหัสบดี',
                                    'friday' => 'ศุกร์',
                                    'saturday' => 'เสาร์',
                                    'sunday' => 'อาทิตย์',
                                ][$day] ?? $day, $preferred_work_days)) }}
                            @else
                                ยังไม่เลือก
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">มีข้อมูลผู้อ้างอิง</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $has_references ? 'ใช่' : 'ไม่' }}</dd>
                    </div>
                    @if($has_references && $reference_name)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ชื่อผู้อ้างอิง</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $reference_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">เบอร์โทรผู้อ้างอิง</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $reference_phone }}</dd>
                        </div>
                    @endif
                </dl>
            </x-filament::section>
        </x-filament::section>

        {{-- Submit ทั้ง 2 Form --}}
        <x-filament::section>
            <x-slot name="heading">
                ✅ ส่งใบสมัครงาน
            </x-slot>
            
            <x-slot name="description">
                ส่งทั้ง 2 Form พร้อมกัน
            </x-slot>

            <div class="flex gap-4">
                <x-filament::button 
                    type="button"
                    size="lg"
                    wire:click="submitAll">
                    📤 ส่งใบสมัครงาน
                </x-filament::button>
            </div>
        </x-filament::section>

        {{-- คำอธิบาย Components --}}
        <x-filament::section>
            <x-slot name="heading">
                📚 คำอธิบาย Form Components ที่ใช้
            </x-slot>
            
            <div class="space-y-6">
                {{-- TextInput --}}
                <div>
                    <h3 class="text-lg font-semibold mb-2">1. TextInput</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <strong>ใช้สำหรับ:</strong> รับข้อความสั้นๆ, ตัวเลข, อีเมล, เบอร์โทร
                    </p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-x-auto text-xs"><code>TextInput::make('first_name')
    ->label('ชื่อ')
    ->required()
    ->maxLength(100)
    ->placeholder('กรุณากรอกชื่อ')
    ->mask('999-999-9999') // สำหรับเบอร์โทร
    ->email() // สำหรับอีเมล
    ->numeric() // สำหรับตัวเลข
    ->prefix('฿') // เพิ่มสัญลักษณ์ด้านหน้า
    ->suffix('%') // เพิ่มสัญลักษณ์ด้านหลัง</code></pre>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        <strong>ประเภทข้อมูล:</strong> string, int, float
                    </p>
                </div>

                {{-- DatePicker --}}
                <div>
                    <h3 class="text-lg font-semibold mb-2">2. DatePicker</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <strong>ใช้สำหรับ:</strong> เลือกวันที่
                    </p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-x-auto text-xs"><code>DatePicker::make('birth_date')
    ->label('วันเกิด')
    ->required()
    ->displayFormat('d/m/Y')
    ->native(false)
    ->minDate(now()->subYears(65))
    ->maxDate(now()->subYears(18))</code></pre>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        <strong>ประเภทข้อมูล:</strong> string (Y-m-d format)
                    </p>
                </div>

                {{-- Select --}}
                <div>
                    <h3 class="text-lg font-semibold mb-2">3. Select</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <strong>ใช้สำหรับ:</strong> เลือกจากรายการ (เลือกได้ 1 หรือหลายค่า)
                    </p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-x-auto text-xs"><code>Select::make('gender')
    ->label('เพศ')
    ->options([
        'male' => 'ชาย',
        'female' => 'หญิง',
    ])
    ->required()
    ->native(false)
    ->searchable() // ค้นหาได้
    ->preload() // โหลดข้อมูลล่วงหน้า
    ->multiple() // เลือกได้หลายค่า
    ->live() // อัปเดตแบบ real-time</code></pre>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        <strong>ประเภทข้อมูล:</strong> string (single), array (multiple)
                    </p>
                </div>

                {{-- Textarea --}}
                <div>
                    <h3 class="text-lg font-semibold mb-2">4. Textarea</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <strong>ใช้สำหรับ:</strong> รับข้อความหลายบรรทัด
                    </p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-x-auto text-xs"><code>Textarea::make('address')
    ->label('ที่อยู่')
    ->required()
    ->rows(4)
    ->maxLength(500)
    ->placeholder('กรุณากรอกที่อยู่')</code></pre>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        <strong>ประเภทข้อมูล:</strong> string
                    </p>
                </div>

                {{-- Radio --}}
                <div>
                    <h3 class="text-lg font-semibold mb-2">5. Radio</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <strong>ใช้สำหรับ:</strong> เลือกตัวเลือกเดียว (แสดงเป็นปุ่ม)
                    </p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-x-auto text-xs"><code>Radio::make('work_experience')
    ->label('มีประสบการณ์หรือไม่')
    ->options([
        'yes' => 'มีประสบการณ์',
        'no' => 'ไม่มีประสบการณ์',
    ])
    ->required()
    ->inline() // แสดงในแนวนอน
    ->live() // อัปเดตแบบ real-time</code></pre>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        <strong>ประเภทข้อมูล:</strong> string
                    </p>
                </div>

                {{-- Toggle --}}
                <div>
                    <h3 class="text-lg font-semibold mb-2">6. Toggle</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <strong>ใช้สำหรับ:</strong> สวิตช์เปิด/ปิด
                    </p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-x-auto text-xs"><code>Toggle::make('has_vehicle')
    ->label('มีรถยนต์ส่วนตัว')
    ->default(false)
    ->live() // อัปเดตแบบ real-time</code></pre>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        <strong>ประเภทข้อมูล:</strong> boolean (true/false)
                    </p>
                </div>

                {{-- Checkbox --}}
                <div>
                    <h3 class="text-lg font-semibold mb-2">7. Checkbox</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <strong>ใช้สำหรับ:</strong> ช่องทำเครื่องหมาย
                    </p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-x-auto text-xs"><code>Checkbox::make('agree_terms')
    ->label('ยอมรับข้อกำหนด')
    ->required()
    ->default(false)</code></pre>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        <strong>ประเภทข้อมูล:</strong> boolean (true/false)
                    </p>
                </div>

                {{-- FileUpload --}}
                <div>
                    <h3 class="text-lg font-semibold mb-2">8. FileUpload</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <strong>ใช้สำหรับ:</strong> อัปโหลดไฟล์ (รูปภาพ, PDF, เอกสาร)
                    </p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-x-auto text-xs"><code>FileUpload::make('resume')
    ->label('Resume / CV')
    ->acceptedFileTypes(['application/pdf', 'application/msword'])
    ->maxSize(5120) // 5MB
    ->directory('job-applications/resumes')
    ->visibility('private')
    ->downloadable()
    ->previewable()
    ->openable()
    ->multiple() // อัปโหลดได้หลายไฟล์
    ->required()</code></pre>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        <strong>ประเภทข้อมูล:</strong> array (file paths)
                    </p>
                </div>

                {{-- Conditional Display --}}
                <div>
                    <h3 class="text-lg font-semibold mb-2">9. Conditional Display (->visible())</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <strong>ใช้สำหรับ:</strong> แสดง field ตามเงื่อนไข
                    </p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-x-auto text-xs"><code>TextInput::make('years_of_experience')
    ->label('จำนวนปีที่ทำงาน')
    ->visible(fn ($get) => $get('work_experience') === 'yes')
    ->required(fn ($get) => $get('work_experience') === 'yes')</code></pre>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        <strong>หลักการ:</strong> ใช้ <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">$get()</code> เพื่ออ่านค่าจาก field อื่น
                    </p>
                </div>

                {{-- Live Updates --}}
                <div>
                    <h3 class="text-lg font-semibold mb-2">10. Real-time Updates (->live())</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <strong>ใช้สำหรับ:</strong> อัปเดตค่าแบบ real-time
                    </p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-x-auto text-xs"><code>Select::make('nationality')
    ->live() // อัปเดตแบบ real-time
    ->debounce(500) // รอ 500ms ก่อนอัปเดต

Toggle::make('has_references')
    ->live() // เมื่อเปิด/ปิด จะอัปเดตทันที</code></pre>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        <strong>หลักการ:</strong> เมื่อค่าเปลี่ยน จะเรียก Livewire hooks อัตโนมัติ (เช่น <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">updatedHasReferences()</code>)
                    </p>
                </div>
            </div>
        </x-filament::section>

        {{-- สรุปหลักการ --}}
        <x-filament::section>
            <x-slot name="heading">
                🎓 สรุปหลักการทำงาน
            </x-slot>
            
            <div class="space-y-4 text-sm">
                <div>
                    <h3 class="font-semibold mb-2">1. การประกาศ Properties</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto text-xs"><code>public ?string $first_name = null;  // TextInput, Textarea
public ?int $years_of_experience = null;  // TextInput (numeric)
public bool $has_vehicle = false;  // Toggle, Checkbox
public ?string $gender = null;  // Select, Radio
public ?string $birth_date = null;  // DatePicker
public ?array $resume = null;  // FileUpload</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Properties จะเก็บค่าจาก form อัตโนมัติเมื่อผู้ใช้กรอกข้อมูล
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">2. การ Bind Component กับ Property</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto text-xs"><code>TextInput::make('first_name')
    ->label('ชื่อ')
    ->required()</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        ชื่อใน <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">->make('property_name')</code> 
                        ต้องตรงกับชื่อ property
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">3. การส่งค่า (Submit)</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto text-xs"><code>public function submitPersonalInfo(): void
{
    // อ่านค่าจาก properties
    $name = $this->first_name;
    $email = $this->email;
    
    // หรืออ่านค่าทั้งหมด
    $data = [
        'first_name' => $this->first_name,
        'email' => $this->email,
        // ...
    ];
}</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        เมื่อกด Submit ค่าจะถูกส่งมาที่ method นี้
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">4. Conditional Display</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto text-xs"><code>TextInput::make('years_of_experience')
    ->visible(fn ($get) => $get('work_experience') === 'yes')
    ->required(fn ($get) => $get('work_experience') === 'yes')</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        ใช้ <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">->visible()</code> 
                        เพื่อแสดง field ตามเงื่อนไข โดยใช้ <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">$get()</code> 
                        เพื่ออ่านค่าจาก field อื่น
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">5. Livewire Hooks</h3>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-x-auto text-xs"><code>// เมื่อ property เปลี่ยน Livewire จะเรียก method นี้อัตโนมัติ
public function updatedHasReferences(): void
{
    // $this->has_references จะมีค่าอัปเดตแล้ว
    if ($this->has_references) {
        // Logic เมื่อเปิด toggle
    }
}</code></pre>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        ใช้ naming convention: <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">updated{PropertyName}()</code>
                    </p>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
