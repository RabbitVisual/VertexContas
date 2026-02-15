<?php

/**
 * Autor: Reinan Rodrigues
 * Empresa: Vertex Solutions LTDA © 2026
 * Email: r.rodriguesjs@gmail.com
 */

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PanelAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Revenue Metrics
        $totalRevenue = \Modules\Gateways\Models\PaymentLog::where('status', 'succeeded')->sum('amount');
        $monthlyRevenue = \Modules\Gateways\Models\PaymentLog::where('status', 'succeeded')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $revenueLastMonth = \Modules\Gateways\Models\PaymentLog::where('status', 'succeeded')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amount');

        // User Metrics
        $totalUsers = \App\Models\User::count();
        $proUsersCount = \App\Models\User::role('pro_user')->count();
        $freeUsersCount = \App\Models\User::role('free_user')->count();

        // Subscription Metrics (assenturas recorrentes)
        $activeSubscriptionsCount = \Modules\Gateways\Models\Subscription::where('status', 'active')->count();

        $newUsersThisMonth = \App\Models\User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Support Metrics
        $openTicketsCount = \Modules\Core\Models\Ticket::where('status', 'open')->count();

        // Recent Activity
        $recentUsers = \App\Models\User::latest()->take(5)->get();
        $recentPayments = \Modules\Gateways\Models\PaymentLog::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Blog Analytics
        $mostReadPosts = \Modules\Blog\Models\Post::where('status', 'published')->orderBy('views', 'desc')->take(5)->get();
        $totalBlogViews = \Modules\Blog\Models\Post::sum('views');
        $blogConversionRate = $totalBlogViews > 0 ? (($proUsersCount / $totalBlogViews) * 100) : 0;
        $recentComments = \Modules\Blog\Models\Comment::with('post', 'user')->latest()->take(5)->get();

        // Top Authors (Safe approach without assuming User model has posts relationship)
        $topAuthors = \Modules\Blog\Models\Post::select('author_id', DB::raw('count(*) as total'))
            ->where('status', 'published')
            ->groupBy('author_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->with('author')
            ->get();

        // Chart Data (Last 6 Months Revenue)
        $revenueData = [];
        $monthLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthLabels[] = $date->format('M');
            $revenueData[] = \Modules\Gateways\Models\PaymentLog::where('status', 'succeeded')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount');
        }

        return view('paneladmin::index', compact(
            'totalRevenue',
            'monthlyRevenue',
            'revenueLastMonth',
            'totalUsers',
            'proUsersCount',
            'freeUsersCount',
            'newUsersThisMonth',
            'activeSubscriptionsCount',
            'openTicketsCount',
            'recentUsers',
            'recentPayments',
            'revenueData',
            'monthLabels',
            'mostReadPosts',
            'topAuthors',
            'blogConversionRate',
            'totalBlogViews',
            'recentComments'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('paneladmin::create');
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
        return view('paneladmin::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('paneladmin::edit');
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
