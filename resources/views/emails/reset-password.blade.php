@extends('emails.layout')

@section('content')
<p style="margin: 0 0 16px;">Você solicitou a redefinição de senha da sua conta Vertex Contas.</p>

<p style="margin: 0 0 16px;">Clique no botão abaixo para criar uma nova senha. Este link expira em 60 minutos.</p>

<p style="margin: 0;">Se você não solicitou essa alteração, ignore este e-mail. Sua senha permanecerá inalterada.</p>
@endsection

@section('button')
<a href="{{ $resetUrl }}" target="_blank" rel="noopener" style="display: inline-block; padding: 16px 32px; background-color: #4f46e5; color: #ffffff !important; text-decoration: none; font-weight: 700; font-size: 16px; border-radius: 6px;">Redefinir Senha</a>
@endsection
