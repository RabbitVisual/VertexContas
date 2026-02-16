@extends('emails.layout')

@section('content')
<p style="margin: 0 0 16px;">Olá, <strong>{{ $user->first_name }}</strong>!</p>

<p style="margin: 0 0 16px;">Bem-vindo à nova era da sua gestão financeira. Explore a regra 50/30/20 agora.</p>

<p style="margin: 0 0 16px;">Você faz parte de uma plataforma pensada para transformar sua relação com o dinheiro. Comece organizando suas categorias, defina metas e acompanhe seus relatórios para tomar decisões mais inteligentes.</p>

<p style="margin: 0;">Qualquer dúvida, estamos à disposição.</p>
@endsection

@section('button')
<a href="{{ config('app.url') }}" target="_blank" rel="noopener" style="display: inline-block; padding: 12px 24px; background-color: #4f46e5; color: #ffffff !important; text-decoration: none; font-weight: 600; font-size: 14px; border-radius: 6px;">Acessar o Painel</a>
@endsection
