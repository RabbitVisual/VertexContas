<?php

/**
 * Autor: Reinan Rodrigues
 * Empresa: Vertex Solutions LTDA © 2026
 * Email: r.rodriguesjs@gmail.com
 */

namespace Modules\PWA\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PWAController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pwa::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pwa::create');
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
        return view('pwa::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('pwa::edit');
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
