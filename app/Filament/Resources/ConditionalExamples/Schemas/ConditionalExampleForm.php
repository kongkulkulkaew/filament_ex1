<?php

namespace App\Filament\Resources\ConditionalExamples\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ConditionalExampleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ============================================
                // Section 1: ข้อมูลพื้นฐาน
                // ============================================
                Section::make('ข้อมูลพื้นฐาน')
                    ->description('กรุณาเลือกประเภทผู้ใช้และกรอกข้อมูลพื้นฐาน')
                    ->schema([
                        Select::make('user_type')
                            ->label('ประเภทผู้ใช้')
                            ->options([
                                'individual' => 'บุคคลทั่วไป',
                                'company' => 'บริษัท',
                                'organization' => 'องค์กร',
                            ])
                            ->required()
                            ->live() // ต้องใช้ live() เพื่อให้ form re-render เมื่อค่าเปลี่ยน
                            ->default('individual')
                            ->helperText('เลือกประเภทผู้ใช้ - ค่านี้จะควบคุมการแสดง fields อื่นๆ')
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Reset fields เมื่อเปลี่ยน user_type
                                if ($state === 'individual') {
                                    $set('company_name', null);
                                    $set('company_tax_id', null);
                                    $set('organization_type', null);
                                    $set('organization_description', null);
                                } elseif ($state === 'company') {
                                    $set('organization_type', null);
                                    $set('organization_description', null);
                                } else {
                                    $set('company_name', null);
                                    $set('company_tax_id', null);
                                }
                            }),

                        // แสดงค่าตัวแปร user_type
                        ViewField::make('user_type_display')
                            ->label('ค่าตัวแปร user_type ปัจจุบัน')
                            ->view('filament.forms.components.display-variable')
                            ->viewData(fn (Get $get) => [
                                'variable_name' => 'user_type',
                                'variable_value' => $get('user_type') ?? 'ยังไม่ได้เลือก',
                                'color' => match ($get('user_type')) {
                                    'individual' => 'blue',
                                    'company' => 'green',
                                    'organization' => 'purple',
                                    default => 'gray',
                                },
                            ])
                            ->columnSpanFull(),

                        TextInput::make('name')
                            ->label('ชื่อ-นามสกุล / ชื่อบริษัท / ชื่อองค์กร')
                            ->required()
                            ->maxLength(255)
                            ->live()
                            ->helperText(fn (Get $get): string => 'ค่าตัวแปร name: '.($get('name') ?: 'ยังไม่ได้กรอก')),

                        TextInput::make('email')
                            ->label('อีเมล')
                            ->email()
                            ->required()
                            ->live()
                            ->helperText(fn (Get $get): string => 'ค่าตัวแปร email: '.($get('email') ?: 'ยังไม่ได้กรอก')),

                        TextInput::make('phone')
                            ->label('เบอร์โทรศัพท์')
                            ->tel()
                            ->required()
                            ->live()
                            ->helperText(fn (Get $get): string => 'ค่าตัวแปร phone: '.($get('phone') ?: 'ยังไม่ได้กรอก')),
                    ])
                    ->columns(2),

                // ============================================
                // Section 2: ข้อมูลบริษัท (แสดงเมื่อ user_type = 'company')
                // ============================================
                Section::make('ข้อมูลบริษัท')
                    ->description('กรอกข้อมูลบริษัท (แสดงเฉพาะเมื่อเลือกประเภท "บริษัท")')
                    ->schema([
                        TextInput::make('company_name')
                            ->label('ชื่อบริษัท')
                            ->required()
                            ->maxLength(255)
                            ->live()
                            ->visible(fn (Get $get): bool => $get('user_type') === 'company')
                            ->helperText(fn (Get $get): string => 'ค่าตัวแปร company_name: '.($get('company_name') ?: 'ยังไม่ได้กรอก')),

                        TextInput::make('company_tax_id')
                            ->label('เลขประจำตัวผู้เสียภาษี')
                            ->mask('9-9999-99999-99-9')
                            ->required()
                            ->live()
                            ->visible(fn (Get $get): bool => $get('user_type') === 'company')
                            ->helperText(fn (Get $get): string => 'ค่าตัวแปร company_tax_id: '.($get('company_tax_id') ?: 'ยังไม่ได้กรอก')),

                        // แสดงค่าตัวแปรทั้งหมดของบริษัท
                        ViewField::make('company_info_display')
                            ->label('ข้อมูลบริษัททั้งหมด')
                            ->view('filament.forms.components.display-variables')
                            ->viewData(fn (Get $get) => [
                                'variables' => [
                                    'company_name' => $get('company_name') ?? 'ยังไม่ได้กรอก',
                                    'company_tax_id' => $get('company_tax_id') ?? 'ยังไม่ได้กรอก',
                                ],
                            ])
                            ->visible(fn (Get $get): bool => $get('user_type') === 'company')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn (Get $get): bool => $get('user_type') === 'company'),

                // ============================================
                // Section 3: ข้อมูลองค์กร (แสดงเมื่อ user_type = 'organization')
                // ============================================
                Section::make('ข้อมูลองค์กร')
                    ->description('กรอกข้อมูลองค์กร (แสดงเฉพาะเมื่อเลือกประเภท "องค์กร")')
                    ->schema([
                        Select::make('organization_type')
                            ->label('ประเภทองค์กร')
                            ->options([
                                'ngo' => 'องค์กรไม่แสวงหาผลกำไร (NGO)',
                                'foundation' => 'มูลนิธิ',
                                'association' => 'สมาคม',
                            ])
                            ->required()
                            ->live()
                            ->visible(fn (Get $get): bool => $get('user_type') === 'organization')
                            ->helperText(fn (Get $get): string => 'ค่าตัวแปร organization_type: '.($get('organization_type') ?: 'ยังไม่ได้เลือก')),

                        Textarea::make('organization_description')
                            ->label('รายละเอียดองค์กร')
                            ->rows(4)
                            ->maxLength(1000)
                            ->live()
                            ->visible(fn (Get $get): bool => $get('user_type') === 'organization')
                            ->helperText(fn (Get $get): string => 'ค่าตัวแปร organization_description: '.(mb_substr($get('organization_description') ?: 'ยังไม่ได้กรอก', 0, 50))),

                        // แสดงค่าตัวแปรทั้งหมดขององค์กร
                        ViewField::make('organization_info_display')
                            ->label('ข้อมูลองค์กรทั้งหมด')
                            ->view('filament.forms.components.display-variables')
                            ->viewData(fn (Get $get) => [
                                'variables' => [
                                    'organization_type' => $get('organization_type') ?? 'ยังไม่ได้เลือก',
                                    'organization_description' => mb_substr($get('organization_description') ?? 'ยังไม่ได้กรอก', 0, 100),
                                ],
                            ])
                            ->visible(fn (Get $get): bool => $get('user_type') === 'organization')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn (Get $get): bool => $get('user_type') === 'organization'),

                // ============================================
                // Section 4: ส่วนลด (แสดงเมื่อ has_discount = true)
                // ============================================
                Section::make('ส่วนลด')
                    ->description('ตั้งค่าส่วนลด (แสดงเมื่อเลือกต้องการส่วนลด)')
                    ->schema([
                        Checkbox::make('has_discount')
                            ->label('ต้องการส่วนลด')
                            ->live()
                            ->helperText('เลือก checkbox นี้เพื่อแสดง fields ส่วนลด'),

                        Select::make('discount_type')
                            ->label('ประเภทส่วนลด')
                            ->options([
                                'percentage' => 'เปอร์เซ็นต์ (%)',
                                'fixed' => 'จำนวนเงินคงที่ (฿)',
                            ])
                            ->required()
                            ->live()
                            ->visible(fn (Get $get): bool => $get('has_discount') === true)
                            ->helperText(fn (Get $get): string => 'ค่าตัวแปร discount_type: '.($get('discount_type') ?: 'ยังไม่ได้เลือก'))
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Reset discount_value เมื่อเปลี่ยน discount_type
                                $set('discount_value', null);
                            }),

                        TextInput::make('discount_value')
                            ->label(fn (Get $get): string => match ($get('discount_type')) {
                                'percentage' => 'เปอร์เซ็นต์ส่วนลด (%)',
                                'fixed' => 'จำนวนเงินส่วนลด (฿)',
                                default => 'ค่าส่วนลด',
                            })
                            ->numeric()
                            ->required()
                            ->live()
                            ->visible(fn (Get $get): bool => $get('has_discount') === true && filled($get('discount_type')))
                            ->minValue(0)
                            ->maxValue(fn (Get $get): ?float => $get('discount_type') === 'percentage' ? 100 : null)
                            ->suffix(fn (Get $get): string => match ($get('discount_type')) {
                                'percentage' => '%',
                                'fixed' => '฿',
                                default => '',
                            })
                            ->helperText(fn (Get $get): string => 'ค่าตัวแปร discount_value: '.($get('discount_value') ?: 'ยังไม่ได้กรอก')),

                        // แสดงค่าส่วนลดที่คำนวณแล้ว
                        ViewField::make('discount_calculation')
                            ->label('ผลการคำนวณส่วนลด')
                            ->view('filament.forms.components.display-variable')
                            ->viewData(fn (Get $get) => [
                                'variable_name' => 'discount_calculation',
                                'variable_value' => match (true) {
                                    ! $get('has_discount') => 'ไม่มีส่วนลด',
                                    ! filled($get('discount_type')) => 'ยังไม่ได้เลือกประเภทส่วนลด',
                                    ! filled($get('discount_value')) => 'ยังไม่ได้กรอกค่าส่วนลด',
                                    $get('discount_type') === 'percentage' => "{$get('discount_value')}%",
                                    $get('discount_type') === 'fixed' => "฿{$get('discount_value')}",
                                    default => 'ไม่ทราบ',
                                },
                                'color' => $get('has_discount') ? 'green' : 'gray',
                            ])
                            ->visible(fn (Get $get): bool => $get('has_discount') === true)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),

                // ============================================
                // Section 5: การจัดส่ง (แสดงเมื่อ needs_shipping = true)
                // ============================================
                Section::make('ข้อมูลการจัดส่ง')
                    ->description('กรอกข้อมูลการจัดส่ง (แสดงเมื่อเลือกต้องการจัดส่ง)')
                    ->schema([
                        Checkbox::make('needs_shipping')
                            ->label('ต้องการจัดส่ง')
                            ->live()
                            ->helperText('เลือก checkbox นี้เพื่อแสดง fields การจัดส่ง'),

                        Textarea::make('shipping_address')
                            ->label('ที่อยู่จัดส่ง')
                            ->rows(3)
                            ->required()
                            ->live()
                            ->visible(fn (Get $get): bool => $get('needs_shipping') === true)
                            ->helperText(fn (Get $get): string => 'ค่าตัวแปร shipping_address: '.(mb_substr($get('shipping_address') ?: 'ยังไม่ได้กรอก', 0, 50))),

                        Select::make('shipping_method')
                            ->label('วิธีการจัดส่ง')
                            ->options([
                                'standard' => 'มาตรฐาน (3-5 วัน)',
                                'express' => 'ด่วน (1-2 วัน)',
                                'overnight' => 'ด่วนพิเศษ (วันถัดไป)',
                            ])
                            ->required()
                            ->live()
                            ->visible(fn (Get $get): bool => $get('needs_shipping') === true)
                            ->helperText(fn (Get $get): string => 'ค่าตัวแปร shipping_method: '.($get('shipping_method') ?: 'ยังไม่ได้เลือก'))
                            ->afterStateUpdated(function ($state, callable $set) {
                                // ตั้งค่า shipping_cost ตาม shipping_method
                                $shippingCosts = [
                                    'standard' => 50,
                                    'express' => 100,
                                    'overnight' => 200,
                                ];
                                $set('shipping_cost', $shippingCosts[$state] ?? 0);
                            }),

                        TextInput::make('shipping_cost')
                            ->label('ค่าจัดส่ง')
                            ->numeric()
                            ->prefix('฿')
                            ->disabled()
                            ->dehydrated()
                            ->live()
                            ->visible(fn (Get $get): bool => $get('needs_shipping') === true && filled($get('shipping_method')))
                            ->helperText(fn (Get $get): string => 'ค่าตัวแปร shipping_cost: '.($get('shipping_cost') ?: '0').' ฿'),

                        // แสดงข้อมูลการจัดส่งทั้งหมด
                        ViewField::make('shipping_info_display')
                            ->label('ข้อมูลการจัดส่งทั้งหมด')
                            ->view('filament.forms.components.display-variables')
                            ->viewData(fn (Get $get) => [
                                'variables' => [
                                    'needs_shipping' => $get('needs_shipping') ? 'ใช่' : 'ไม่',
                                    'shipping_address' => mb_substr($get('shipping_address') ?? 'ยังไม่ได้กรอก', 0, 50),
                                    'shipping_method' => $get('shipping_method') ?? 'ยังไม่ได้เลือก',
                                    'shipping_cost' => ($get('shipping_cost') ?? 0).' ฿',
                                ],
                            ])
                            ->visible(fn (Get $get): bool => $get('needs_shipping') === true)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),

                // ============================================
                // Section 6: หมายเหตุ
                // ============================================
                Section::make('หมายเหตุ')
                    ->description('หมายเหตุเพิ่มเติม')
                    ->schema([
                        Textarea::make('notes')
                            ->label('หมายเหตุ')
                            ->rows(3)
                            ->maxLength(1000)
                            ->live()
                            ->helperText(fn (Get $get): string => 'ค่าตัวแปร notes: '.(mb_substr($get('notes') ?: 'ยังไม่ได้กรอก', 0, 50)))
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                // ============================================
                // Section 7: สรุปข้อมูลทั้งหมด (แสดงค่าตัวแปรทั้งหมด)
                // ============================================
                Section::make('สรุปข้อมูลทั้งหมด')
                    ->description('แสดงค่าตัวแปรทั้งหมดใน form')
                    ->schema([
                        ViewField::make('all_variables_display')
                            ->label('ค่าตัวแปรทั้งหมด')
                            ->view('filament.forms.components.display-all-variables')
                            ->viewData(fn (Get $get) => [
                                'all_data' => [
                                    'user_type' => $get('user_type') ?? 'ยังไม่ได้เลือก',
                                    'name' => $get('name') ?? 'ยังไม่ได้กรอก',
                                    'email' => $get('email') ?? 'ยังไม่ได้กรอก',
                                    'phone' => $get('phone') ?? 'ยังไม่ได้กรอก',
                                    'company_name' => $get('company_name') ?? 'ยังไม่ได้กรอก',
                                    'company_tax_id' => $get('company_tax_id') ?? 'ยังไม่ได้กรอก',
                                    'organization_type' => $get('organization_type') ?? 'ยังไม่ได้เลือก',
                                    'organization_description' => mb_substr($get('organization_description') ?? 'ยังไม่ได้กรอก', 0, 50),
                                    'has_discount' => $get('has_discount') ? 'ใช่' : 'ไม่',
                                    'discount_type' => $get('discount_type') ?? 'ยังไม่ได้เลือก',
                                    'discount_value' => $get('discount_value') ?? 'ยังไม่ได้กรอก',
                                    'needs_shipping' => $get('needs_shipping') ? 'ใช่' : 'ไม่',
                                    'shipping_address' => mb_substr($get('shipping_address') ?? 'ยังไม่ได้กรอก', 0, 50),
                                    'shipping_method' => $get('shipping_method') ?? 'ยังไม่ได้เลือก',
                                    'shipping_cost' => ($get('shipping_cost') ?? 0).' ฿',
                                    'notes' => mb_substr($get('notes') ?? 'ยังไม่ได้กรอก', 0, 50),
                                ],
                            ])
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
