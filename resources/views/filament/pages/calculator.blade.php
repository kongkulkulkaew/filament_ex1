<x-filament-panels::page>
    @push('styles')
        <style>
            .calculator-container {
                max-width: 400px;
                margin: 0 auto;
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }
            
            .calculator-display {
                background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
                padding: 2rem;
                min-height: 120px;
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
            }
            
            .calculator-expression {
                color: #94a3b8;
                font-size: 0.875rem;
                min-height: 20px;
                text-align: right;
                margin-bottom: 0.5rem;
            }
            
            .calculator-result {
                color: white;
                font-size: 3rem;
                font-weight: 700;
                text-align: right;
                word-break: break-all;
                line-height: 1.2;
            }
            
            .calculator-buttons {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 0.75rem;
                padding: 1rem;
                background: #f8fafc;
            }
            
            .calc-btn {
                min-height: 60px;
                height: 60px;
                border-radius: 8px;
                font-size: 1.25rem;
                font-weight: 600;
                transition: all 0.2s;
                border: none;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                box-sizing: border-box;
            }
            
            .calc-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            }
            
            .calc-btn:active {
                transform: translateY(0);
            }
            
            .calc-btn-number {
                background: white;
                color: #1e293b;
            }
            
            .calc-btn-number:hover {
                background: #f1f5f9;
            }
            
            .calc-btn-operator {
                background: #f59e0b;
                color: white;
            }
            
            .calc-btn-operator:hover {
                background: #d97706;
            }
            
            .calc-btn-clear {
                background: #ef4444;
                color: white;
                grid-column: span 2;
            }
            
            .calc-btn-clear:hover {
                background: #dc2626;
            }
            
            .calc-btn-backspace {
                background: #64748b;
                color: white;
            }
            
            .calc-btn-backspace:hover {
                background: #475569;
            }
            
            .calc-btn-equals {
                background: #10b981;
                color: white;
            }
            
            .calc-btn-equals:hover {
                background: #059669;
            }
            
            .calc-btn-zero {
                grid-column: span 2;
            }
        </style>
    @endpush

    <div class="py-8">
        <div class="calculator-container">
            <!-- Display -->
            <div class="calculator-display">
                <div class="calculator-expression">
                    @if($previousValue && $operator)
                        {{ $previousValue }} {{ $operator }}
                    @endif
                </div>
                <div class="calculator-result">
                    {{ $display }}
                </div>
            </div>
            
            <!-- Buttons -->
            <div class="calculator-buttons">
                <!-- Row 1 -->
                <button
                    wire:click="clear"
                    class="calc-btn calc-btn-clear">
                    Clear
                </button>
                
                <button
                    wire:click="backspace"
                    class="calc-btn calc-btn-backspace">
                    ⌫
                </button>
                
                <button
                    wire:click="setOperator('/')"
                    class="calc-btn calc-btn-operator">
                    ÷
                </button>
                
                <!-- Row 2 -->
                <button
                    wire:click="inputNumber('7')"
                    class="calc-btn calc-btn-number">
                    7
                </button>
                
                <button
                    wire:click="inputNumber('8')"
                    class="calc-btn calc-btn-number">
                    8
                </button>
                
                <button
                    wire:click="inputNumber('9')"
                    class="calc-btn calc-btn-number">
                    9
                </button>
                
                <button
                    wire:click="setOperator('*')"
                    class="calc-btn calc-btn-operator">
                    ×
                </button>
                
                <!-- Row 3 -->
                <button
                    wire:click="inputNumber('4')"
                    class="calc-btn calc-btn-number">
                    4
                </button>
                
                <button
                    wire:click="inputNumber('5')"
                    class="calc-btn calc-btn-number">
                    5
                </button>
                
                <button
                    wire:click="inputNumber('6')"
                    class="calc-btn calc-btn-number">
                    6
                </button>
                
                <button
                    wire:click="setOperator('-')"
                    class="calc-btn calc-btn-operator">
                    −
                </button>
                
                <!-- Row 4 -->
                <button
                    wire:click="inputNumber('1')"
                    class="calc-btn calc-btn-number">
                    1
                </button>
                
                <button
                    wire:click="inputNumber('2')"
                    class="calc-btn calc-btn-number">
                    2
                </button>
                
                <button
                    wire:click="inputNumber('3')"
                    class="calc-btn calc-btn-number">
                    3
                </button>
                
                <button
                    wire:click="setOperator('+')"
                    class="calc-btn calc-btn-operator">
                    +
                </button>
                
                <!-- Row 5 -->
                <button
                    wire:click="inputNumber('0')"
                    class="calc-btn calc-btn-number calc-btn-zero">
                    0
                </button>
                
                <button
                    wire:click="inputDecimal"
                    class="calc-btn calc-btn-number">
                    .
                </button>
                
                <button
                    wire:click="calculate"
                    class="calc-btn calc-btn-equals">
                    =
                </button>
            </div>
        </div>
    </div>
</x-filament-panels::page>