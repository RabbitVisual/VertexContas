<?php

declare(strict_types=1);

namespace Modules\HomePage\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\Models\LegalDocument;

class LegalController extends Controller
{
    /**
     * Display a single legal document by slug (public, active only).
     */
    public function show(string $slug)
    {
        $document = LegalDocument::active()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('homepage::legal.show', compact('document'));
    }
}
