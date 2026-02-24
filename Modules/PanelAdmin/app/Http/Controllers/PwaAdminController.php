<?php

declare(strict_types=1);

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\PWA\Http\Requests\StorePwaVersionRequest;
use Modules\PWA\Http\Requests\UpdatePwaVersionRequest;
use Modules\PWA\Models\PwaInstall;
use Modules\PWA\Models\PwaVersion;

class PwaAdminController extends Controller
{
    public function dashboard(): View
    {
        $totalInstalls = PwaInstall::count();
        $installsByVersion = PwaInstall::query()
            ->selectRaw('app_version, count(*) as total')
            ->groupBy('app_version')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('total', 'app_version');
        $proCount = PwaInstall::where('is_pro', true)->count();
        $freeCount = $totalInstalls - $proCount;
        $latestRelease = PwaVersion::latestRelease();

        return view('paneladmin::pwa.dashboard', compact(
            'totalInstalls',
            'installsByVersion',
            'proCount',
            'freeCount',
            'latestRelease'
        ));
    }

    public function installs(Request $request): View
    {
        $query = PwaInstall::with('user')->orderByDesc('installed_at');

        if ($request->filled('version')) {
            $query->where('app_version', $request->get('version'));
        }
        if ($request->filled('platform')) {
            $query->where('platform', $request->get('platform'));
        }
        if ($request->filled('plan')) {
            if ($request->get('plan') === 'pro') {
                $query->where('is_pro', true);
            } elseif ($request->get('plan') === 'free') {
                $query->where('is_pro', false);
            }
        }

        $installs = $query->paginate(25)->withQueryString();

        return view('paneladmin::pwa.installs', compact('installs'));
    }

    public function versions(): View
    {
        $versions = PwaVersion::orderByDesc('released_at')->paginate(15);

        return view('paneladmin::pwa.versions.index', compact('versions'));
    }

    public function createVersion(): View
    {
        return view('paneladmin::pwa.versions.create', ['version' => new PwaVersion]);
    }

    public function storeVersion(StorePwaVersionRequest $request): RedirectResponse
    {
        PwaVersion::create($request->validated());

        return redirect()->route('admin.pwa.versions.index')->with('success', 'Versão publicada com sucesso.');
    }

    public function editVersion(PwaVersion $version): View
    {
        return view('paneladmin::pwa.versions.edit', compact('version'));
    }

    public function updateVersion(UpdatePwaVersionRequest $request, PwaVersion $version): RedirectResponse
    {
        $version->update($request->validated());

        return redirect()->route('admin.pwa.versions.index')->with('success', 'Versão atualizada com sucesso.');
    }

    public function destroyVersion(PwaVersion $version): RedirectResponse
    {
        $version->delete();

        return redirect()->route('admin.pwa.versions.index')->with('success', 'Versão removida.');
    }
}
