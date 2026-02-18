<?php

declare(strict_types=1);

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Models\EmailTemplate;

class EmailTemplateController extends Controller
{
    /**
     * List all email templates (Mail Central – Template Manager).
     */
    public function index(): View
    {
        $templates = EmailTemplate::orderBy('key')->get();

        return view('paneladmin::mail.templates.index', compact('templates'));
    }

    /**
     * Show edit form for a template.
     */
    public function edit(EmailTemplate $template): View
    {
        return view('paneladmin::mail.templates.edit', compact('template'));
    }

    /**
     * Update template (subject, content_html, variables_hint, description, is_html).
     */
    public function update(Request $request, EmailTemplate $template)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'content_html' => 'required|string',
            'variables_hint' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:500',
            'is_html' => 'nullable|boolean',
        ]);
        $data['is_html'] = $request->boolean('is_html');

        $template->update($data);

        return redirect()
            ->route('admin.mail.templates.index')
            ->with('success', 'Template atualizado com sucesso.');
    }
}
