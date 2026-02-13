<?php

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;

class SupportController extends Controller
{
    public function index()
    {
        // Future: Fetch tickets from PanelSuporte module
        $tickets = [];

        return view('paneladmin::support.index', compact('tickets'));
    }
}
