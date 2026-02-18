<?php

declare(strict_types=1);

namespace Modules\HomePage\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Modules\Core\Models\CookieConsent;

class CookieConsentController extends Controller
{
    /**
     * Store cookie consent for audit (90-day validity).
     */
    public function store(Request $request): JsonResponse
    {
        $accepted = $request->boolean('accepted', true);

        if (\Illuminate\Support\Facades\Schema::hasTable('cookie_consents')) {
            CookieConsent::create([
                'user_id' => $request->user()?->id,
                'session_id' => $request->user() ? null : $request->session()->getId(),
                'ip_address' => $request->ip() ?? '',
                'user_agent' => $request->userAgent(),
                'accepted' => $accepted,
                'expires_at' => now()->addDays(90),
            ]);
        }

        $cookie = Cookie::make(
            'cookie_consent',
            $accepted ? 'accepted' : 'rejected',
            60 * 24 * 90, // 90 days in minutes
            '/',
            null,
            request()->secure(),
            true,
            false,
            'lax'
        );

        return response()->json(['success' => true])
            ->cookie($cookie);
    }
}
