<?php

declare(strict_types=1);

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SupportAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Core\Services\SettingService;
use Modules\Core\Services\TourService;

class SettingsController extends Controller
{
    protected $settingService;

    protected $tourService;

    public function __construct(SettingService $settingService, TourService $tourService)
    {
        $this->settingService = $settingService;
        $this->tourService = $tourService;
    }

    protected const ALLOWED_TABS = ['general', 'branding', 'security', 'features', 'mail', 'blog', 'documents', 'pusher', 'homepage', 'tools', 'gemini'];

    /**
     * Show the settings form.
     */
    public function index()
    {
        $tab = request()->get('tab', 'general');
        if (! in_array($tab, self::ALLOWED_TABS, true)) {
            $tab = 'general';
        }

        $general = $this->settingService->getByGroup('general');
        $branding = $this->settingService->getByGroup('branding');
        $security = $this->settingService->getByGroup('security');
        $features = $this->settingService->getByGroup('features');
        $mail = $this->settingService->getByGroup('mail');
        $blog = $this->settingService->getByGroup('blog');
        $documents = $this->settingService->getByGroup('document_templates');
        $pusher = $this->settingService->getByGroup('pusher');
        $homepage = $this->settingService->getByGroup('homepage');
        $gemini = $this->settingService->getByGroup('gemini');
        $notifications = $this->settingService->getByGroup('notifications');

        $settingsTourId = $this->tourService->getTourForRoute(request()->route()?->getName(), true);
        $settingsTourSteps = $settingsTourId ? $this->tourService->getStepsForTour($settingsTourId, true) : [];

        return view('paneladmin::settings.index', compact('general', 'branding', 'security', 'features', 'mail', 'blog', 'documents', 'pusher', 'homepage', 'gemini', 'notifications', 'tab', 'settingsTourId', 'settingsTourSteps'));
    }

    /**
     * Update general settings.
     */
    public function updateGeneral(Request $request)
    {
        $data = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_description' => 'required|string|max:500',
            'app_url' => 'required|url',
            'app_timezone' => ['required', 'string', 'timezone'],
            'app_locale' => 'required|string|in:pt_BR,en',
            'panel_user_name' => 'nullable|string|max:100',
            'panel_admin_name' => 'nullable|string|max:100',
            'panel_suporte_name' => 'nullable|string|max:100',
            'maintenance_message' => 'nullable|string|max:500',
        ]);

        foreach ($data as $key => $value) {
            $this->settingService->set($key, $value ?? '', 'general');
        }

        // Handle Checkbox for Maintenance Mode (if not in request, it's false)
        $maintenanceMode = $request->has('maintenance_mode');
        $this->settingService->set('maintenance_mode', $maintenanceMode, 'general', 'boolean');

        // Mensagem customizável de manutenção
        $this->settingService->set('maintenance_message', $data['maintenance_message'] ?? null, 'general');

        $tab = in_array($request->get('tab'), self::ALLOWED_TABS, true) ? $request->get('tab') : 'general';

        return redirect()->route('admin.settings.index', ['tab' => $tab])->with('success', 'Configurações gerais atualizadas com sucesso!');
    }

    /**
     * Update branding settings.
     */
    public function updateBranding(Request $request)
    {
        $request->validate([
            'app_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'app_favicon' => 'nullable|image|mimes:png,ico|max:512',
            'logo_user' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'logo_user_dark' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'logo_admin' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'logo_admin_dark' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'logo_suporte' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'logo_suporte_dark' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'favicon' => 'nullable|image|mimes:png,ico|max:512',
        ]);

        $logoKeys = [
            'app_logo',
            'app_favicon',
            'logo_user',
            'logo_user_dark',
            'logo_admin',
            'logo_admin_dark',
            'logo_suporte',
            'logo_suporte_dark',
            'favicon',
        ];

        foreach ($logoKeys as $key) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $ext = $file->getClientOriginalExtension();
                $filename = str_replace('_', '-', $key) . '.' . $ext;
                $path = $file->storeAs('logos', $filename, 'public');
                $this->settingService->set($key, 'storage/' . $path, 'branding');
            }
        }

        $tab = in_array($request->get('tab'), self::ALLOWED_TABS, true) ? $request->get('tab') : 'branding';

        return redirect()->route('admin.settings.index', ['tab' => $tab])->with('success', 'Marca atualizada com sucesso!');
    }

    /**
     * Test SMTP configuration.
     */
    public function testMail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $tab = in_array($request->get('tab'), self::ALLOWED_TABS, true) ? $request->get('tab') : 'mail';
        $mailer = $this->settingService->get('mail_mailer', 'smtp');

        if ($mailer === 'log') {
            return redirect()->route('admin.settings.index', ['tab' => $tab])
                ->with('warning', 'Driver atual é "Log": a mensagem foi registrada no log, mas nenhum e-mail real é enviado. Altere o driver para SMTP e salve as configurações para enviar para a caixa de entrada.');
        }

        if ($mailer === 'smtp') {
            $host = $this->settingService->get('mail_host');
            $fromAddress = $this->settingService->get('mail_from_address');
            if (empty($host) || empty($fromAddress)) {
                return redirect()->route('admin.settings.index', ['tab' => $tab])
                    ->with('error', 'Salve as configurações de e-mail (Host SMTP e E-mail Remetente) antes de enviar o teste.');
            }
        }

        try {
            $encryption = $this->settingService->get('mail_encryption');
            if ($encryption === 'null' || $encryption === '') {
                $encryption = null;
            }

            $smtpPassword = $this->settingService->get('mail_password');
            if ($mailer === 'smtp' && empty($smtpPassword)) {
                return redirect()->route('admin.settings.index', ['tab' => $tab])
                    ->with('error', 'A senha SMTP não está salva. Informe a senha no campo "Senha SMTP" e clique em "Salvar Configurações de E-mail" antes de enviar o teste.');
            }

            config([
                'mail.default' => $mailer,
                'mail.mailers.smtp.host' => $this->settingService->get('mail_host'),
                'mail.mailers.smtp.port' => (int) $this->settingService->get('mail_port', 587),
                'mail.mailers.smtp.username' => $this->settingService->get('mail_username'),
                'mail.mailers.smtp.password' => $smtpPassword,
                'mail.mailers.smtp.encryption' => $encryption,
                'mail.mailers.smtp.timeout' => 15,
                'mail.from.address' => $this->settingService->get('mail_from_address'),
                'mail.from.name' => $this->settingService->get('mail_from_name') ?: 'Vertex Contas',
            ]);

            Mail::purge($mailer);

            Mail::raw('Este é um e-mail de teste do Vertex Contas. Se você recebeu esta mensagem, a configuração SMTP está correta.', function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Teste de Configuração SMTP - Vertex Contas');
            });

            return redirect()->route('admin.settings.index', ['tab' => $tab])
                ->with('success', 'E-mail de teste enviado com sucesso para '.$request->email.'. Verifique a caixa de entrada e a pasta de spam.');
        } catch (\Throwable $e) {
            Log::error('Admin test mail failed', [
                'to' => $request->email,
                'mailer' => $mailer,
                'host' => $mailer === 'smtp' ? $this->settingService->get('mail_host') : null,
                'message' => $e->getMessage(),
                'exception' => (string) $e,
            ]);
            $userMessage = $e->getMessage();
            if (str_contains($userMessage, 'Connection timed out') || str_contains($userMessage, 'stream_socket_client')) {
                $userMessage = 'Não foi possível conectar ao servidor SMTP. Verifique Host, Porta (465 para SSL), firewall do servidor e se a Hostinger permite SMTP. Detalhe: '.$userMessage;
            }
            return redirect()->route('admin.settings.index', ['tab' => $tab])
                ->with('error', 'Erro ao enviar e-mail: '.$userMessage);
        }
    }

    /**
     * Update mail settings.
     */
    public function updateMail(Request $request)
    {
        $rules = [
            'mail_mailer' => 'required|string|in:smtp,ses,mailgun,resend,log',
            'mail_host' => 'nullable|required_if:mail_mailer,smtp|string',
            'mail_port' => 'nullable|required_if:mail_mailer,smtp|integer',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string|in:tls,ssl,null',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string|max:255',
        ];
        $data = $request->validate($rules);

        foreach ($data as $key => $value) {
            // Only update password if provided, otherwise keep existing
            if ($key === 'mail_password' && empty($value)) {
                continue;
            }

            $encrypt = $key === 'mail_password';
            $this->settingService->set($key, $value, 'mail', 'string', $encrypt);
        }

        $tab = in_array($request->get('tab'), self::ALLOWED_TABS, true) ? $request->get('tab') : 'mail';

        return redirect()->route('admin.settings.index', ['tab' => $tab])->with('success', 'Configurações de e-mail atualizadas com sucesso!');
    }

    /**
     * Send manual notification.
     */
    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'audience' => 'required|in:all,role',
            'role' => 'nullable|required_if:audience,role|exists:roles,name',
            'type' => 'required|in:info,success,warning,danger',
        ]);

        $service = app(\Modules\Notifications\Services\NotificationService::class);

        if ($request->audience === 'all') {
            $service->sendSystemWide($request->title, $request->message, $request->type);
        } else {
            $service->sendToRole($request->role, $request->title, $request->message, $request->type);
        }

        return back()->with('success', 'Notificação enviada com sucesso!');
    }

    /**
     * Update document templates settings (company info, limits).
     */
    public function updateDocumentTemplates(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_legal_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string|max:500',
            'company_cnpj' => 'nullable|string|max:20',
            'company_phone' => 'nullable|string|max:30',
            'company_email' => 'nullable|email|max:255',
            'document_footer_text' => 'nullable|string|max:500',
            'limit_download_invoice_per_day' => 'required|integer|min:0|max:999',
            'limit_download_report_per_day' => 'required|integer|min:0|max:999',
            'limit_ai_report_per_month' => 'required|integer|min:0|max:99',
        ]);

        $data['company_cnpj'] = lgpd_clean_cnpj($data['company_cnpj'] ?? null) ?: null;
        $data['company_phone'] = lgpd_clean_phone($data['company_phone'] ?? null) ?: null;

        foreach ($data as $key => $value) {
            $type = in_array($key, ['limit_download_invoice_per_day', 'limit_download_report_per_day', 'limit_ai_report_per_month']) ? 'integer' : 'string';
            $this->settingService->set($key, $value, 'document_templates', $type);
        }

        $tab = in_array($request->get('tab'), self::ALLOWED_TABS, true) ? $request->get('tab') : 'documents';

        return redirect()->route('admin.settings.index', ['tab' => $tab])->with('success', 'Configurações de documentos atualizadas com sucesso!');
    }

    /**
     * Update Pusher/broadcasting settings.
     */
    public function updatePusher(Request $request)
    {
        $data = $request->validate([
            'broadcast_connection' => 'required|in:log,pusher,null',
            'pusher_app_id' => 'nullable|string|max:100',
            'pusher_app_key' => 'nullable|string|max:100',
            'pusher_app_secret' => 'nullable|string|max:255',
            'pusher_app_cluster' => 'nullable|string|max:50',
        ]);

        foreach ($data as $key => $value) {
            if ($key === 'pusher_app_secret' && empty($value)) {
                continue;
            }
            $encrypt = $key === 'pusher_app_secret';
            $this->settingService->set($key, $value ?? '', 'pusher', 'string', $encrypt);
        }

        $tab = in_array($request->get('tab'), self::ALLOWED_TABS, true) ? $request->get('tab') : 'pusher';

        return redirect()->route('admin.settings.index', ['tab' => $tab])->with('success', 'Configurações do Pusher atualizadas com sucesso!');
    }

    /**
     * Update security settings (Segurança Avançada).
     */
    public function updateSecurity(Request $request)
    {
        $data = $request->validate([
            'security_login_max_attempts' => 'required|integer|min:1|max:20',
            'security_lockout_time' => 'required|integer|min:1|max:120',
            'security_session_lifetime' => 'required|integer|min:15|max:10080',
            'security_single_session' => 'nullable',
            'security_password_min_chars' => 'required|integer|min:6|max:32',
            'security_password_require_special' => 'nullable',
            'security_audit_retention_days' => 'required|integer|min:30|max:365',
            'security_inspection_max_duration' => 'required|integer|min:60|max:86400',
            'recaptcha_enabled' => 'nullable',
            'recaptcha_site_key' => 'nullable|string|max:255',
            'recaptcha_secret_key' => 'nullable|string|max:255',
            'recaptcha_min_score' => 'nullable|numeric|min:0|max:1',
        ]);

        $changed = [];
        $securityKeys = [
            'security_login_max_attempts' => ['value' => (int) $data['security_login_max_attempts'], 'type' => 'integer'],
            'security_lockout_time' => ['value' => (int) $data['security_lockout_time'], 'type' => 'integer'],
            'security_session_lifetime' => ['value' => (int) $data['security_session_lifetime'], 'type' => 'integer'],
            'security_single_session' => ['value' => $request->has('security_single_session'), 'type' => 'boolean'],
            'security_password_min_chars' => ['value' => (int) $data['security_password_min_chars'], 'type' => 'integer'],
            'security_password_require_special' => ['value' => $request->has('security_password_require_special'), 'type' => 'boolean'],
            'security_audit_retention_days' => ['value' => (int) $data['security_audit_retention_days'], 'type' => 'integer'],
            'security_inspection_max_duration' => ['value' => (int) $data['security_inspection_max_duration'], 'type' => 'integer'],
        ];

        foreach ($securityKeys as $key => $config) {
            $old = $this->settingService->get($key);
            $this->settingService->set($key, $config['value'], 'security', $config['type']);
            if ($old != $config['value']) {
                $changed[$key] = $config['value'];
            }
        }

        $this->settingService->set('recaptcha_enabled', $request->has('recaptcha_enabled'), 'security', 'boolean');
        $this->settingService->set('recaptcha_site_key', $data['recaptcha_site_key'] ?? '', 'security');
        if (! empty($data['recaptcha_secret_key'] ?? null)) {
            $this->settingService->set('recaptcha_secret_key', $data['recaptcha_secret_key'], 'security', 'string', true);
        }
        $this->settingService->set('recaptcha_min_score', (float) ($data['recaptcha_min_score'] ?? 0.5), 'security', 'string');

        if (! empty($changed) && Auth::check()) {
            SupportAuditLog::create([
                'agent_id' => Auth::id(),
                'user_id' => Auth::id(),
                'action' => 'security_policy_updated',
                'metadata' => ['changed' => $changed],
                'ip_address' => $request->ip(),
            ]);
        }

        $tab = in_array($request->get('tab'), self::ALLOWED_TABS, true) ? $request->get('tab') : 'security';

        return redirect()->route('admin.settings.index', ['tab' => $tab])->with('success', 'Configurações de segurança atualizadas!');
    }

    /**
     * Clear audit logs older than retention period.
     */
    public function clearAuditLogs(Request $request)
    {
        $retentionDays = (int) $this->settingService->get('security_audit_retention_days', 90);
        $cutoff = now()->subDays($retentionDays);

        $deleted = SupportAuditLog::where('created_at', '<', $cutoff)->delete();

        return redirect()->route('admin.settings.index', ['tab' => 'security'])
            ->with('success', "{$deleted} registro(s) de auditoria removido(s).");
    }

    /**
     * Update features (Vertex Chat, etc).
     */
    public function updateFeatures(Request $request)
    {
        $vertexChatEnabled = $request->has('vertex_chat_enabled');
        $this->settingService->set('vertex_chat_enabled', $vertexChatEnabled, 'features', 'boolean');

        $request->validate([
            'notifications_retention_days' => 'nullable|integer|min:1|max:365',
            'notifications_auto_clean_read' => 'nullable',
        ]);
        $retentionDays = $request->input('notifications_retention_days');
        $this->settingService->set('notifications_retention_days', (int) ($retentionDays !== null && $retentionDays !== '' ? $retentionDays : 90), 'notifications', 'integer');
        $this->settingService->set('notifications_auto_clean_read', $request->has('notifications_auto_clean_read'), 'notifications', 'boolean');

        $tab = in_array($request->get('tab'), self::ALLOWED_TABS, true) ? $request->get('tab') : 'features';

        return redirect()->route('admin.settings.index', ['tab' => $tab])->with('success', 'Recursos atualizados com sucesso!');
    }

    /**
     * Update homepage settings (conteúdo, contato, redes sociais, cookie, SEO).
     */
    public function updateHomepage(Request $request)
    {
        $data = $request->validate([
            'homepage_hero_subtitle' => 'nullable|string|max:500',
            'homepage_footer_description' => 'nullable|string|max:1000',
            'homepage_contact_email' => 'nullable|email|max:255',
            'homepage_contact_email_privacy' => 'nullable|email|max:255',
            'homepage_social_facebook' => 'nullable|url|max:255',
            'homepage_social_instagram' => 'nullable|url|max:255',
            'homepage_social_linkedin' => 'nullable|url|max:255',
            'homepage_cookie_consent_message' => 'nullable|string|max:1000',
            'homepage_meta_description' => 'nullable|string|max:500',
            'homepage_meta_keywords' => 'nullable|string|max:500',
        ]);

        foreach ($data as $key => $value) {
            $this->settingService->set($key, $value ?? '', 'homepage');
        }

        $this->settingService->set('homepage_cookie_consent_enabled', $request->has('homepage_cookie_consent_enabled'), 'homepage', 'boolean');
        $this->settingService->set('homepage_show_back_to_top', $request->has('homepage_show_back_to_top'), 'homepage', 'boolean');

        $tab = in_array($request->get('tab'), self::ALLOWED_TABS, true) ? $request->get('tab') : 'homepage';

        return redirect()->route('admin.settings.index', ['tab' => $tab])->with('success', 'Configurações da Homepage atualizadas com sucesso!');
    }

    /**
     * Update blog settings.
     */
    public function updateBlog(Request $request)
    {
        $enableGuestComments = $request->has('enable_guest_comments');
        $this->settingService->set('enable_guest_comments', $enableGuestComments, 'blog', 'boolean');

        $autoApproveComments = $request->has('auto_approve_comments');
        $this->settingService->set('auto_approve_comments', $autoApproveComments, 'blog', 'boolean');

        $tab = in_array($request->get('tab'), self::ALLOWED_TABS, true) ? $request->get('tab') : 'blog';

        return redirect()->route('admin.settings.index', ['tab' => $tab])->with('success', 'Configurações do blog atualizadas com sucesso!');
    }

    /**
     * Update Gemini / Vertex Bot IA settings.
     */
    public function updateGemini(Request $request)
    {
        $geminiEnabled = $request->has('gemini_enabled');
        $this->settingService->set('gemini_enabled', $geminiEnabled, 'gemini', 'boolean');

        $apiKey = $request->input('gemini_api_key');
        if ($apiKey !== null && $apiKey !== '') {
            $this->settingService->set('gemini_api_key', $apiKey, 'gemini', 'string', true);
        }

        $tab = in_array($request->get('tab'), self::ALLOWED_TABS, true) ? $request->get('tab') : 'gemini';

        return redirect()->route('admin.settings.index', ['tab' => $tab])->with('success', 'Configurações da IA (Vertex Bot) atualizadas com sucesso!');
    }
}
