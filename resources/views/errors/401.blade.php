@php
    $code = 401;
    $title = 'Não autorizado';
    $message = 'Opa! Parece que você precisa fazer login para acessar isso.';
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
    <div class="error-code font-audiowide text-6xl sm:text-7xl font-bold">401</div>
    <h1 class="text-xl sm:text-2xl font-semibold text-slate-200 mt-4">{{ $title }}</h1>
    <p class="mt-3 text-slate-400 text-base sm:text-lg max-w-md mx-auto">{{ $message }}</p>
    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 rounded-lg bg-[#11c76f]/20 text-[#11c76f] hover:bg-[#11c76f]/30 font-medium transition-colors">
        <i class="fa-pro fa-solid fa-right-to-bracket"></i> Fazer login
    </a>
</div>
@endsection
