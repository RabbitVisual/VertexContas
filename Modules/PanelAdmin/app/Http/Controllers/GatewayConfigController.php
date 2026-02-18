<?php

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Gateways\Models\Gateway;

class GatewayConfigController extends Controller
{
    /**
     * Display a listing of gateways.
     */
    public function index()
    {
        $gateways = Gateway::orderBy('name')->get();
        $totalCount = $gateways->count();
        $activeCount = $gateways->where('is_active', true)->count();
        $liveCount = $gateways->where('mode', 'live')->count();

        return view('paneladmin::gateways.index', compact('gateways', 'totalCount', 'activeCount', 'liveCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gateway $gateway)
    {
        return view('paneladmin::gateways.edit', compact('gateway'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gateway $gateway)
    {
        $request->validate([
            'api_key' => 'nullable|string|max:500',
            'secret_key' => 'nullable|string|max:500',
            'webhook_secret' => 'nullable|string|max:500',
            'mode' => 'required|in:sandbox,live',
        ]);

        $data = ['mode' => $request->input('mode')];

        // Only update key fields when a non-empty value was submitted (avoid overwriting with empty)
        if ($request->filled('api_key')) {
            $data['api_key'] = $request->input('api_key');
        }
        if ($request->filled('secret_key')) {
            $data['secret_key'] = $request->input('secret_key');
        }
        if ($request->filled('webhook_secret')) {
            $data['webhook_secret'] = $request->input('webhook_secret');
        }

        $gateway->update($data);

        return redirect()->route('admin.gateways.index')
            ->with('success', "Configurações do {$gateway->name} atualizadas com sucesso!");
    }

    /**
     * Toggle gateway status.
     */
    public function toggle(Gateway $gateway)
    {
        $gateway->update(['is_active' => !$gateway->is_active]);

        $status = $gateway->is_active ? 'ativado' : 'desativado';
        return back()->with('success', "Gateway {$gateway->name} {$status} com sucesso!");
    }
}
