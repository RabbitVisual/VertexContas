@props(['value', 'prefix' => 'R$'])

@php
    $inspectionId = session('impersonate_inspection_id');
    $inspection = $inspectionId ? \Modules\Core\Models\Inspection::find($inspectionId) : null;
    $shouldHide = $inspection && $inspection->status === 'active' && !$inspection->show_financial_data;

    $formattedValue = is_numeric($value) ? number_format($value, 2, ',', '.') : $value;
@endphp

<span {{ $attributes->merge(['class' => 'transition-all duration-500 ' . ($shouldHide ? 'blur-[6px] select-none pointer-events-none opacity-50 grayscale' : '')]) }}
      @if($shouldHide) title="Oculto por privacidade durante a inspeção" @endif>
    {{ $prefix }} {{ $shouldHide ? '00.000,00' : $formattedValue }}
</span>
