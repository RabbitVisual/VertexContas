<?php

declare(strict_types=1);

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Models\LegalDocument;
use Modules\Core\Models\UserLegalAcceptance;

class LegalManagerController extends Controller
{
    /**
     * Display the Central Legal index - list all legal documents.
     */
    public function index()
    {
        $documents = LegalDocument::orderBy('slug')->get();

        return view('paneladmin::legal.index', compact('documents'));
    }

    /**
     * Show the form for editing a legal document.
     */
    public function edit(LegalDocument $document)
    {
        return view('paneladmin::legal.edit', compact('document'));
    }

    /**
     * Update the specified legal document.
     */
    public function update(Request $request, LegalDocument $document)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'version' => 'required|string|max:50',
        ]);

        $document->update([
            'title' => $request->title,
            'content' => $request->content,
            'version' => $request->version,
            'is_active' => $request->boolean('is_active'),
            'requires_acceptance' => $request->boolean('requires_acceptance'),
        ]);

        if ($request->boolean('force_reamendment')) {
            UserLegalAcceptance::where('legal_document_id', $document->id)->delete();
        }

        return redirect()->route('admin.legal.index')
            ->with('success', 'Documento legal atualizado com sucesso!');
    }
}
