@php
    $code = 500;
    $title = 'Erro no servidor';
    $message = 'Algo deu errado do nosso lado. Já estamos trabalhando nisso.';
@endphp
@extends('errors.layout')

@section('content')
<style>
    @keyframes flicker {
        0%, 19%, 21%, 23%, 25%, 54%, 56%, 100% { opacity: 1; filter: drop-shadow(0 0 10px #11c76f) drop-shadow(0 0 20px #11c76f); }
        20%, 24%, 55% { opacity: 0.9; filter: drop-shadow(0 0 5px #11c76f); }
    }
    @keyframes glow-pulse {
        0%, 100% { text-shadow: 0 0 20px rgba(17,199,111,0.6), 0 0 40px rgba(17,199,111,0.4); }
        50% { text-shadow: 0 0 30px rgba(17,199,111,0.8), 0 0 60px rgba(17,199,111,0.5); }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .error-code-neon {
        font-family: 'Audiowide', sans-serif;
        color: #11c76f;
        font-size: clamp(6rem, 20vw, 10rem);
        font-weight: 700;
        line-height: 1;
        animation: flicker 4s infinite, glow-pulse 3s ease-in-out infinite;
        text-shadow: 0 0 20px rgba(17,199,111,0.6), 0 0 40px rgba(17,199,111,0.4);
    }
    .error-text-block { animation: fadeInUp 0.6s ease-out 0.2s both; }
</style>

<div class="error-code-neon font-audiowide select-none" aria-hidden="true">500</div>
<div class="error-text-block mt-4">
    <h1 class="text-xl sm:text-2xl font-semibold text-slate-200">{{ $title }}</h1>
    <p class="mt-3 text-slate-400 text-base sm:text-lg max-w-md mx-auto">{{ $message }}</p>
</div>
@endsection
