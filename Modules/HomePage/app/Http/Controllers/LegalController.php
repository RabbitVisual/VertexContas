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

    /**
     * Display Terms of Use (slug: termos-de-uso).
     */
    public function showTerms()
    {
        return $this->show('termos-de-uso');
    }

    /**
     * Display Privacy Policy (slug: privacidade).
     */
    public function showPrivacy()
    {
        return $this->show('privacidade');
    }
}
