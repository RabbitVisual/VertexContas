<?php

namespace Modules\PanelSuporte\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SupportReportController extends Controller
{
    public function index()
    {
        // Advanced stats for support reports
        $totalTickets = Ticket::count();
        $ticketsByStatus = Ticket::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $recentTicketsByDay = Ticket::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $ticketsByPriority = Ticket::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->get();

        $averageResponseTime = DB::table('ticket_messages')
            ->where('is_admin_reply', true)
            ->join('tickets', 'tickets.id', '=', 'ticket_messages.ticket_id')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, tickets.created_at, ticket_messages.created_at)) as avg_time'))
            ->first();

        $resolvedTickets = Ticket::where('status', 'closed')->count();
        $resolutionRate = $totalTickets > 0 ? ($resolvedTickets / $totalTickets) * 100 : 0;

        return view('panelsuporte::reports.index', compact(
            'totalTickets',
            'ticketsByStatus',
            'recentTicketsByDay',
            'ticketsByPriority',
            'averageResponseTime',
            'resolutionRate'
        ));
    }
}
