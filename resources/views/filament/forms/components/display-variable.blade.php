@php
    $colorClasses = [
        'blue' => 'bg-blue-50 border-blue-200 text-blue-800',
        'green' => 'bg-green-50 border-green-200 text-green-800',
        'purple' => 'bg-purple-50 border-purple-200 text-purple-800',
        'gray' => 'bg-gray-50 border-gray-200 text-gray-800',
    ];
    $colorClass = $colorClasses[$color ?? 'gray'] ?? $colorClasses['gray'];
@endphp

<div class="rounded-lg border p-3 {{ $colorClass }}">
    <div class="text-xs font-semibold uppercase tracking-wide mb-1">ตัวแปร: {{ $variable_name }}</div>
    <div class="text-sm font-mono">{{ $variable_value }}</div>
</div>
