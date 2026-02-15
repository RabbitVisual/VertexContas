@props(['value', 'prefix' => 'R$'])

@php
    $shouldHide = \Modules\Core\Services\InspectionGuard::shouldHideFinancialData();
    $displayValue = \Modules\Core\Services\InspectionGuard::maskValue($value, $prefix);
    $maskClasses = \Modules\Core\Services\InspectionGuard::maskClasses();
@endphp

<span {{ $attributes->merge(['class' => \App\Helpers\SensitiveHelper::sensitiveClass() . ' transition-all duration-500 ' . $maskClasses]) }}
      @if($shouldHide) title="Oculto por privacidade durante a inspeção" @endif>
    {{ $displayValue }}
</span>
