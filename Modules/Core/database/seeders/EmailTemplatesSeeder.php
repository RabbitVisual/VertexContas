<?php

declare(strict_types=1);

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\EmailTemplate;

class EmailTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'key' => 'welcome_user',
                'subject' => 'Bem-vindo ao Vertex Contas',
                'description' => 'E-mail enviado após o cadastro do usuário.',
                'variables_hint' => 'name, app_url',
                'is_html' => true,
                'content_html' => '<p style="margin: 0 0 16px;">Olá, <strong>{{ name }}</strong>!</p>
<p style="margin: 0 0 16px;">Bem-vindo à nova era da sua gestão financeira. Explore a regra 50/30/20 agora.</p>
<p style="margin: 0 0 16px;">Você faz parte de uma plataforma pensada para transformar sua relação com o dinheiro.</p>
<p style="margin: 16px 0 0;"><a href="{{ app_url }}" target="_blank" rel="noopener" style="display: inline-block; padding: 12px 24px; background-color: #4f46e5; color: #ffffff !important; text-decoration: none; font-weight: 600; font-size: 14px; border-radius: 6px;">Acessar o Painel</a></p>',
            ],
            [
                'key' => 'password_reset',
                'subject' => 'Redefinir sua senha - Vertex Contas',
                'description' => 'E-mail de recuperação de senha com link de redefinição.',
                'variables_hint' => 'name, email, reset_link, app_url',
                'is_html' => true,
                'content_html' => '<p style="margin: 0 0 16px;">Você solicitou a redefinição de senha da sua conta Vertex Contas.</p>
<p style="margin: 0 0 16px;">Clique no botão abaixo para criar uma nova senha. Este link expira em 60 minutos.</p>
<p style="margin: 16px 0 0;"><a href="{{ reset_link }}" target="_blank" rel="noopener" style="display: inline-block; padding: 16px 32px; background-color: #4f46e5; color: #ffffff !important; text-decoration: none; font-weight: 700; font-size: 16px; border-radius: 6px;">Redefinir Senha</a></p>',
            ],
            [
                'key' => 'pro_activated',
                'subject' => 'Parabéns! Você agora é VIP - Vertex Contas',
                'description' => 'E-mail enviado após confirmação da assinatura PRO.',
                'variables_hint' => 'name, app_url',
                'is_html' => true,
                'content_html' => '<p style="margin: 0 0 16px;">Parabéns, <strong>{{ name }}</strong>! Você agora é VIP.</p>
<p style="margin: 0 0 16px;">Sua assinatura foi confirmada. Aproveite todos os benefícios do seu plano.</p>
<p style="margin: 16px 0 0;"><a href="{{ app_url }}" target="_blank" rel="noopener" style="display: inline-block; padding: 12px 24px; background-color: #4f46e5; color: #ffffff !important; text-decoration: none; font-weight: 600; font-size: 14px; border-radius: 6px;">Acessar o Painel PRO</a></p>',
            ],
            [
                'key' => 'ticket_replied',
                'subject' => 'Nova resposta no seu ticket - Vertex Contas',
                'description' => 'Notificação quando o suporte responde a um ticket.',
                'variables_hint' => 'name, ticket_id, link, app_url',
                'is_html' => true,
                'content_html' => '<p style="margin: 0 0 16px;">Olá, <strong>{{ name }}</strong>!</p>
<p style="margin: 0 0 16px;">Há uma nova resposta no seu ticket #{{ ticket_id }}.</p>
<p style="margin: 16px 0 0;"><a href="{{ link }}" target="_blank" rel="noopener" style="display: inline-block; padding: 12px 24px; background-color: #4f46e5; color: #ffffff !important; text-decoration: none; font-weight: 600; font-size: 14px; border-radius: 6px;">Ver resposta</a></p>',
            ],
            [
                'key' => 'monthly_report_ready',
                'subject' => 'Seu relatório mensal está pronto - Vertex Contas',
                'description' => 'E-mail quando o relatório mensal é gerado.',
                'variables_hint' => 'name, link, app_url',
                'is_html' => true,
                'content_html' => '<p style="margin: 0 0 16px;">Olá, <strong>{{ name }}</strong>!</p>
<p style="margin: 0 0 16px;">Seu relatório mensal está disponível.</p>
<p style="margin: 16px 0 0;"><a href="{{ link }}" target="_blank" rel="noopener" style="display: inline-block; padding: 12px 24px; background-color: #4f46e5; color: #ffffff !important; text-decoration: none; font-weight: 600; font-size: 14px; border-radius: 6px;">Abrir relatório</a></p>',
            ],
        ];

        foreach ($templates as $data) {
            EmailTemplate::updateOrCreate(
                ['key' => $data['key']],
                $data
            );
        }
    }
}
