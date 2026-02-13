<?php

/**
 * Autor: Reinan Rodrigues
 * Empresa: Vertex Solutions LTDA © 2026
 * Email: r.rodriguesjs@gmail.com
 */

namespace Modules\Gateways\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GatewaysController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:gateways.view')->only(['index', 'show']);
        $this->middleware('permission:gateways.create')->only(['create', 'store']);
        $this->middleware('permission:gateways.edit')->only(['edit', 'update']);
        $this->middleware('permission:gateways.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('gateways::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gateways::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('gateways::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('gateways::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
