<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Form 1: แบบปกติ (Submit) --}}
        <x-filament::section>
            <x-slot name="heading">
                📝 Form 1: แบบปกติ (Submit)
            </x-slot>
            
            <x-slot name="description">
                <strong>หลักการ:</strong> ค่าจะถูกส่งเมื่อกดปุ่ม Submit เท่านั้น
                <br>ใช้ <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">wire:submit="submitForm1"</code>
            </x-slot>

            <form wire:submit="submitForm1">
                {{ $this->getForm1Schema() }}

                <div class="flex gap-4 mt-4">
                    <x-filament::button type="submit">
                        บันทึก (Submit)
                    </x-filament::button>
                    
                    <x-filament::button 
                        type="button" 
                        color="gray"
                        wire:click="resetForm1">
                        รีเซ็ต
                    </x-filament::button>
                </div>
            </form>

            <x-filament::section class="mt-4">
                <x-slot name="heading">
                    ค่าปัจจุบัน
                </x-slot>
                
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ชื่อ</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $form1_name ?? 'ยังไม่กรอก' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">อีเมล</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $form1_email ?? 'ยังไม่กรอก' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ข้อความ</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ Str::limit($form1_message ?? 'ยังไม่กรอก', 30) }}</dd>
                    </div>
                </dl>
            </x-filament::section>
        </x-filament::section>

        {{-- Form 2: แบบ Real-time (Live) --}}
        <x-filament::section>
            <x-slot name="heading">
                ⚡ Form 2: แบบ Real-time (Live)
            </x-slot>
            
            <x-slot name="description">
                <strong>หลักการ:</strong> ค่าจะถูกส่งทันทีเมื่อพิมพ์ (ใช้ ->live())
                <br>เรียก method อัตโนมัติเมื่อค่าเปลี่ยน (ใช้ ->afterStateUpdated())
            </x-slot>

            <form wire:submit.prevent>
                {{ $this->getForm2Schema() }}
            </form>

            @if($form2_result)
                <div class="mt-4 p-4 bg-success-50 dark:bg-success-900/20 border border-success-300 dark:border-success-600 rounded-lg">
                    <p class="text-success-800 dark:text-success-200 font-semibold">{{ $form2_result }}</p>
                </div>
            @endif

            <x-filament::section class="mt-4">
                <x-slot name="heading">
                    ค่าปัจจุบัน (อัปเดตแบบ Real-time)
                </x-slot>
                
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ค้นหา</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $form2_search ?? 'ยังไม่กรอก' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ตัวเลข</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $form2_number ?? '0' }}</dd>
                    </div>
                </dl>
            </x-filament::section>
        </x-filament::section>

        {{-- Form 3: แบบ Conditional (แสดงตามเงื่อนไข) --}}
        <x-filament::section>
            <x-slot name="heading">
                🔀 Form 3: แบบ Conditional
            </x-slot>
            
            <x-slot name="description">
                <strong>หลักการ:</strong> แสดง field ตามเงื่อนไข (ใช้ ->visible())
                <br>ใช้ <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">$get()</code> เพื่ออ่านค่าจาก field อื่น
            </x-slot>

            <form wire:submit.prevent>
                {{ $this->getForm3Schema() }}
            </form>

            <x-filament::section class="mt-4">
                <x-slot name="heading">
                    ค่าปัจจุบัน
                </x-slot>
                
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ประเภท</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">
                            @if($form3_type === 'student') นักเรียน
                            @elseif($form3_type === 'employee') พนักงาน
                            @elseif($form3_type === 'company') บริษัท
                            @else ยังไม่เลือก
                            @endif
                        </dd>
                    </div>
                    @if($form3_type === 'student')
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">รหัสนักเรียน</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $form3_student_id ?? 'ยังไม่กรอก' }}</dd>
                        </div>
                    @elseif($form3_type === 'employee')
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">รหัสพนักงาน</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $form3_employee_code ?? 'ยังไม่กรอก' }}</dd>
                        </div>
                    @elseif($form3_type === 'company')
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ชื่อบริษัท</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $form3_company_name ?? 'ยังไม่กรอก' }}</dd>
                        </div>
                    @endif
                </dl>
            </x-filament::section>
        </x-filament::section>

        {{-- Form 4: แบบ Multiple Steps --}}
        <x-filament::section>
            <x-slot name="heading">
                📑 Form 4: แบบ Multiple Steps
            </x-slot>
            
            <x-slot name="description">
                <strong>หลักการ:</strong> แบ่ง form เป็นหลายขั้นตอน
                <br>ใช้ property <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">$currentStep</code> เพื่อเก็บขั้นตอนปัจจุบัน
            </x-slot>

            {{-- Step Indicator --}}
            <div class="mb-6 flex items-center justify-center gap-2">
                @for($i = 1; $i <= 3; $i++)
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold
                            @if($i === $currentStep) bg-primary-600 text-white ring-2 ring-primary-600 ring-offset-2
                            @elseif($i < $currentStep) bg-success-600 text-white
                            @else bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400
                            @endif">
                            @if($i < $currentStep)
                                ✓
                            @else
                                {{ $i }}
                            @endif
                        </div>
                        @if($i < 3)
                            <div class="w-16 h-1 mx-2
                                @if($i < $currentStep) bg-success-600
                                @else bg-gray-300 dark:bg-gray-600
                                @endif">
                            </div>
                        @endif
                    </div>
                @endfor
            </div>

            <form wire:submit.prevent>
                {{ $this->getForm4Schema() }}

                <div class="flex gap-4 mt-4">
                    @if($currentStep > 1)
                        <x-filament::button 
                            type="button" 
                            color="gray"
                            wire:click="previousStep">
                            ← ย้อนกลับ
                        </x-filament::button>
                    @endif

                    @if($currentStep < 3)
                        <x-filament::button 
                            type="button"
                            wire:click="nextStep">
                            ถัดไป →
                        </x-filament::button>
                    @else
                        <x-filament::button 
                            type="button"
                            wire:click="submitForm4">
                            บันทึก
                        </x-filament::button>
                    @endif
                </div>
            </form>

            <x-filament::section class="mt-4">
                <x-slot name="heading">
                    ค่าปัจจุบัน
                </x-slot>
                
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ขั้นตอน</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $currentStep }}/3</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ชื่อ</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $step1_name ?? 'ยังไม่กรอก' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">อีเมล</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $step1_email ?? 'ยังไม่กรอก' }}</dd>
                    </div>
                    @if($currentStep >= 2)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ที่อยู่</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ Str::limit($step2_address ?? 'ยังไม่กรอก', 30) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">เบอร์โทร</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $step2_phone ?? 'ยังไม่กรอก' }}</dd>
                        </div>
                    @endif
                    @if($currentStep >= 3)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">หมายเหตุ</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ Str::limit($step3_notes ?? 'ยังไม่กรอก', 30) }}</dd>
                        </div>
                    @endif
                </dl>
            </x-filament::section>
        </x-filament::section>

        {{-- Form 5: แบบ Auto-calculate --}}
        <x-filament::section>
            <x-slot name="heading">
                🧮 Form 5: แบบ Auto-calculate
            </x-slot>
            
            <x-slot name="description">
                <strong>หลักการ:</strong> คำนวณอัตโนมัติเมื่อค่าเปลี่ยน (ใช้ ->afterStateUpdated())
                <br>ใช้ <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">$set()</code> เพื่อกำหนดค่าให้ field อื่น
                <br>ใช้ <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">$get()</code> เพื่ออ่านค่าจาก field อื่น
            </x-slot>

            <form wire:submit.prevent>
                {{ $this->getForm5Schema() }}

                <div class="mt-4">
                    <x-filament::button 
                        type="button"
                        wire:click="submitForm5">
                        บันทึก
                    </x-filament::button>
                </div>
            </form>

            <x-filament::section class="mt-4">
                <x-slot name="heading">
                    ค่าปัจจุบัน (คำนวณอัตโนมัติ)
                </x-slot>
                
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ราคา</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">฿{{ number_format($form5_price ?? 0, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">จำนวน</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $form5_quantity ?? 1 }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ส่วนลด</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $form5_discount ?? 0 }}%</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ยอดรวม</dt>
                        <dd class="mt-1 text-lg font-bold text-success-600 dark:text-success-400 font-mono">฿{{ number_format($form5_total ?? 0, 2) }}</dd>
                    </div>
                </dl>
            </x-filament::section>
        </x-filament::section>

        {{-- สรุปหลักการ --}}
        <x-filament::section>
            <x-slot name="heading">
                📚 สรุปหลักการส่งรับค่าในหลาย Form
            </x-slot>
            
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold mb-2">1. Form แบบปกติ (Submit)</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                        <strong>หลักการ:</strong> ค่าจะถูกส่งเมื่อกด Submit เท่านั้น
                    </p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-x-auto text-xs"><code>// ใน Form
TextInput::make('form1_name')
    ->label('ชื่อ')

// ใน View
&lt;form wire:submit="submitForm1"&gt;
    &lt;input type="text" wire:model="form1_name" /&gt;
    &lt;button type="submit"&gt;Submit&lt;/button&gt;
&lt;/form&gt;

// ใน Method
public function submitForm1(): void
{
    $name = $this->form1_name;
}</code></pre>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-2">2. Form แบบ Real-time (Live)</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                        <strong>หลักการ:</strong> ค่าจะถูกส่งทันทีเมื่อพิมพ์ (ไม่ต้องกด Submit)
                    </p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-x-auto text-xs"><code>TextInput::make('form2_search')
    ->live() // อัปเดตแบบ real-time
    ->afterStateUpdated(function ($state) {
        $this->updateSearchResult();
    })</code></pre>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-2">3. Form แบบ Conditional</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                        <strong>หลักการ:</strong> แสดง field ตามเงื่อนไข โดยใช้ <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">$get()</code>
                    </p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-x-auto text-xs"><code>Select::make('form3_type')
    ->live()

TextInput::make('form3_student_id')
    ->visible(fn ($get) => $get('form3_type') === 'student')</code></pre>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-2">4. Form แบบ Multiple Steps</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                        <strong>หลักการ:</strong> แบ่ง form เป็นหลายขั้นตอน โดยใช้ property เพื่อเก็บขั้นตอนปัจจุบัน
                    </p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-x-auto text-xs"><code>public int $currentStep = 1;

TextInput::make('step1_name')
    ->visible(fn () => $this->currentStep === 1)

public function nextStep(): void
{
    $this->currentStep++;
}</code></pre>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-2">5. Form แบบ Auto-calculate</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                        <strong>หลักการ:</strong> คำนวณอัตโนมัติเมื่อค่าเปลี่ยน โดยใช้ <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">$set()</code> และ <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">$get()</code>
                    </p>
                    <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-x-auto text-xs"><code>TextInput::make('form5_price')
    ->live()
    ->afterStateUpdated(function ($state, callable $set, callable $get) {
        $total = $get('form5_price') * $get('form5_quantity');
        $set('form5_total', $total);
    })</code></pre>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
