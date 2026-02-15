@php
    $code = 400;
    $title = 'Requisição inválida';
    $message = 'Algo não bateu. Verifique os dados e tente novamente.';
@endphp
@extends('errors.layout')

@section('content')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .error-block { animation: fadeInUp 0.6s ease-out both; }
    .error-code { color: #11c76f; text-shadow: 0 0 15px rgba(17,199,111,0.5); }
</style>

<div class="error-block">
    <div class="error-code font-audiowide text-6xl sm:text-7xl font-bold">400</div>
    <h1 class="text-xl sm:text-2xl font-semibold text-slate-200 mt-4">{{ $title }}</h1>
    <p class="mt-3 text-slate-400 text-base sm:text-lg max-w-md mx-auto">{{ $message }}</p>
</div>
@endsection
