<?php

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Services\SettingService;

class SettingsController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Show the settings form.
     */
    public function index()
    {
        $general = $this->settingService->getByGroup('general');
        $branding = $this->settingService->getByGroup('branding');
        $mail = $this->settingService->getByGroup('mail');

        return view('paneladmin::settings.index', compact('general', 'branding', 'mail'));
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
            'app_timezone' => 'required|string',
            'app_locale' => 'required|string|in:pt_BR,en',
        ]);

        foreach ($data as $key => $value) {
            $this->settingService->set($key, $value, 'general');
        }

        // Handle Checkbox for Maintenance Mode (if not in request, it's false)
        $maintenanceMode = $request->has('maintenance_mode');
        $this->settingService->set('maintenance_mode', $maintenanceMode, 'general', 'boolean');

        return back()->with('success', 'Configurações gerais atualizadas com sucesso!');
    }

    /**
     * Update branding settings.
     */
    public function updateBranding(Request $request)
    {
        $request->validate([
            'app_logo' => 'nullable|image|mimes:png,jpg,svg|max:2048',
            'app_favicon' => 'nullable|image|mimes:png,ico|max:512',
        ]);

        if ($request->hasFile('app_logo')) {
            $path = $request->file('app_logo')->store('logos', 'public');
            $this->settingService->set('app_logo', 'storage/'.$path, 'branding');
        }

        if ($request->hasFile('app_favicon')) {
            $path = $request->file('app_favicon')->store('logos', 'public');
            $this->settingService->set('app_favicon', 'storage/'.$path, 'branding');
        }

        return back()->with('success', 'Marca atualizada com sucesso!');
    }

    /**
     * Test SMTP configuration.
     */
    public function testMail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            // Force config for this request
            config([
                'mail.default' => $this->settingService->get('mail_mailer', 'smtp'),
                'mail.mailers.smtp.host' => $this->settingService->get('mail_host'),
                'mail.mailers.smtp.port' => $this->settingService->get('mail_port'),
                'mail.mailers.smtp.username' => $this->settingService->get('mail_username'),
                'mail.mailers.smtp.password' => $this->settingService->get('mail_password'),
                'mail.mailers.smtp.encryption' => $this->settingService->get('mail_encryption'),
                'mail.from.address' => $this->settingService->get('mail_from_address'),
                'mail.from.name' => $this->settingService->get('mail_from_name'),
            ]);

            \Illuminate\Support\Facades\Mail::raw('Este é um e-mail de teste do VertexContas.', function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Teste de Configuração SMTP');
            });

            return back()->with('success', 'E-mail de teste enviado com sucesso para '.$request->email);
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao enviar e-mail: '.$e->getMessage());
        }
    }

    /**
     * Update mail settings.
     */
    public function updateMail(Request $request)
    {
        $data = $request->validate([
            'mail_mailer' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        foreach ($data as $key => $value) {
            // Only update password if provided, otherwise keep existing
            if ($key === 'mail_password' && empty($value)) {
                continue;
            }

            $encrypt = $key === 'mail_password';
            $this->settingService->set($key, $value, 'mail', 'string', $encrypt);
        }

        return back()->with('success', 'Configurações de e-mail atualizadas com sucesso!');
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
}
