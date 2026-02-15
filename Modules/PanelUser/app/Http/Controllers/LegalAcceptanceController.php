<?php

declare(strict_types=1);

namespace Modules\PanelUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Models\LegalDocument;
use Modules\Core\Models\UserLegalAcceptance;

class LegalAcceptanceController extends Controller
{
    /**
     * Display the Legal Wall - documents requiring acceptance.
     */
    public function index()
    {
        $user = auth()->user();

        $requiredDocs = LegalDocument::active()
            ->requiresAcceptance()
            ->orderBy('slug')
            ->get();

        $pending = $requiredDocs->filter(function (LegalDocument $doc) use ($user) {
            $acceptance = UserLegalAcceptance::where('user_id', $user->id)
                ->where('legal_document_id', $doc->id)
                ->first();

            return ! $acceptance || $acceptance->version !== $doc->version;
        })->values();

        return view('paneluser::legal.acceptance', [
            'pendingDocuments' => $pending,
        ]);
    }

    /**
     * Store acceptance for a document.
     */
    public function store(Request $request)
    {
        $request->validate([
            'legal_document_id' => 'required|exists:legal_documents,id',
        ]);

        $document = LegalDocument::active()
            ->requiresAcceptance()
            ->findOrFail($request->legal_document_id);

        UserLegalAcceptance::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'legal_document_id' => $document->id,
            ],
            [
                'version' => $document->version,
                'accepted_at' => now(),
                'ip_address' => $request->ip(),
            ]
        );

        $pending = LegalDocument::active()
            ->requiresAcceptance()
            ->get()
            ->filter(function (LegalDocument $doc) use ($request) {
                $acceptance = UserLegalAcceptance::where('user_id', $request->user()->id)
                    ->where('legal_document_id', $doc->id)
                    ->first();

                return ! $acceptance || $acceptance->version !== $doc->version;
            })->values();

        if ($pending->isEmpty()) {
            return redirect()->route('paneluser.index')
                ->with('success', 'Obrigado! Você aceitou todos os termos necessários.');
        }

        return redirect()->route('paneluser.legal.acceptance')
            ->with('success', 'Documento aceito. Leia e aceite o próximo.');
    }
}
