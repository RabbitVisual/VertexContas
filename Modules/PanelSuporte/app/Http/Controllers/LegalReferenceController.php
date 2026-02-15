<?php

declare(strict_types=1);

namespace Modules\PanelSuporte\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\Models\LegalDocument;

class LegalReferenceController extends Controller
{
    /**
     * Display all active legal documents for support agents to reference.
     */
    public function index()
    {
        $documents = LegalDocument::active()->orderBy('slug')->get();

        return view('panelsuporte::wiki.legal-reference', compact('documents'));
    }
}
