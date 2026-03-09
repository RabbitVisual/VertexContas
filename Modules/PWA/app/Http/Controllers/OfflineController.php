<?php

declare(strict_types=1);

namespace Modules\PWA\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class OfflineController extends Controller
{
    public function __invoke(): View
    {
        return view('pwa::offline');
    }
}
