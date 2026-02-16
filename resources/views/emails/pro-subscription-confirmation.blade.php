@extends('emails.layout')

@section('content')
<p style="margin: 0 0 16px;">Parabéns, <strong>{{ $user->first_name }}</strong>! Você agora é VIP.</p>

<p style="margin: 0 0 16px;">Sua assinatura Vertex PRO foi confirmada. Aproveite todos os benefícios exclusivos:</p>

<ul style="margin: 0 0 16px; padding-left: 20px; color: #374151;">
    <li style="margin-bottom: 8px;"><strong>Chat VIP</strong> – Suporte em tempo real com nossa equipe</li>
    <li style="margin-bottom: 8px;"><strong>Consultoria PDF</strong> – Relatórios profissionais para análise e planejamento</li>
</ul>

<p style="margin: 0;">Obrigado por fazer parte da elite Vertex Contas.</p>
@endsection

@section('button')
<a href="{{ config('app.url') }}" target="_blank" rel="noopener" style="display: inline-block; padding: 12px 24px; background-color: #4f46e5; color: #ffffff !important; text-decoration: none; font-weight: 600; font-size: 14px; border-radius: 6px;">Acessar o Painel PRO</a>
@endsection
