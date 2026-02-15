@props([
    'mask', // cpf | cnpj | phone | money | date | percent | cep | card
    'type' => 'text',
])

@php
    $placeholders = [
        'cpf' => '000.000.000-00',
        'cnpj' => '00.000.000/0000-00',
        'phone' => '(00) 00000-0000',
        'money' => '0,00',
        'date' => 'dd/mm/aaaa',
        'percent' => '0,00%',
        'cep' => '00000-000',
        'card' => '0000 0000 0000 0000',
    ];
    $placeholder = $placeholders[$mask] ?? '';
    $inputType = in_array($mask, ['date']) ? 'text' : $type;
    $mergeAttrs = ['placeholder' => $placeholder];
    if ($mask) {
        $mergeAttrs['x-mask'] = "'{$mask}'";
    }
@endphp

<input
    type="{{ $inputType }}"
    {{ $attributes->merge($mergeAttrs) }}
>
