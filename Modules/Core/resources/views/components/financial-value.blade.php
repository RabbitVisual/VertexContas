@props(['value', 'prefix' => 'R$'])

@php
    $shouldHide = \Modules\Core\Services\InspectionGuard::shouldHideFinancialData();
    $displayValue = format_currency($value, $prefix);
    $maskClasses = \Modules\Core\Services\InspectionGuard::maskClasses();
@endphp

<span {{ $attributes->merge(['class' => \App\Helpers\SensitiveHelper::sensitiveClass() . ' transition-all duration-500 ' . $maskClasses]) }}
      @if($shouldHide) title="Oculto por privacidade durante a inspeção" @endif>
    {{ $displayValue }}
</span>
