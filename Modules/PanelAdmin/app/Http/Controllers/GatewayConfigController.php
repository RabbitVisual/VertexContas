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
        $gateways = Gateway::all();
        return view('paneladmin::gateways.index', compact('gateways'));
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
        $data = $request->validate([
            'api_key' => 'nullable|string',
            'secret_key' => 'nullable|string',
            'webhook_secret' => 'nullable|string',
            'mode' => 'required|in:sandbox,live',
        ]);

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
