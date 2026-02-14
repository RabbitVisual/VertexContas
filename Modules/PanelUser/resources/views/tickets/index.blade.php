<x-paneluser::layouts.master :title="__('Help Center')">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ __('Help Center') }}</h1>
                <p class="text-gray-500 dark:text-gray-400 font-medium mt-1">{{ __('Manage your requests and get support.') }}</p>
            </div>
            <a href="{{ route('user.tickets.create') }}" class="group relative px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl shadow-lg shadow-primary-500/30 transition-all hover:scale-105 active:scale-95 flex items-center gap-3 overflow-hidden">
                <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                <i class="fa-solid fa-plus relative z-10"></i>
                <span class="relative z-10">{{ __('New Ticket') }}</span>
            </a>
        </div>

        <!-- Stats / Overview -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col items-center justify-center text-center">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">{{ __('Total') }}</span>
                <span class="text-2xl font-black text-gray-900 dark:text-white">{{ $tickets->count() }}</span>
            </div>
             <div class="bg-emerald-50 dark:bg-emerald-900/10 p-4 rounded-2xl border border-emerald-100 dark:border-emerald-500/20 flex flex-col items-center justify-center text-center">
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-1">{{ __('Resolved') }}</span>
                <span class="text-2xl font-black text-emerald-700 dark:text-emerald-300">{{ $tickets->where('status', 'resolved')->count() + $tickets->where('status', 'closed')->count() }}</span>
            </div>
            <div class="bg-amber-50 dark:bg-amber-900/10 p-4 rounded-2xl border border-amber-100 dark:border-amber-500/20 flex flex-col items-center justify-center text-center">
                <span class="text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-widest mb-1">{{ __('Pending') }}</span>
                <span class="text-2xl font-black text-amber-700 dark:text-amber-300">{{ $tickets->where('status', 'pending')->count() }}</span>
            </div>
             <div class="bg-blue-50 dark:bg-blue-900/10 p-4 rounded-2xl border border-blue-100 dark:border-blue-500/20 flex flex-col items-center justify-center text-center">
                <span class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-1">{{ __('Open') }}</span>
                <span class="text-2xl font-black text-blue-700 dark:text-blue-300">{{ $tickets->where('status', 'open')->count() }}</span>
            </div>
        </div>

        <!-- Ticket List Timeline -->
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest px-1">{{ __('Ticket History') }}</h3>

            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                @forelse($tickets as $ticket)
                    <a href="{{ route('user.tickets.show', $ticket) }}" class="block group relative border-b border-gray-50 dark:border-gray-700 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors p-6">
                        <div class="flex items-start justify-between gap-4">
                            <!-- Icon & Content -->
                            <div class="flex items-start gap-4">
                                @php
                                    $lastMessage = $ticket->messages->last();
                                    $lastUser = $lastMessage ? $lastMessage->user : $ticket->user;
                                    $isSupport = $lastMessage ? $lastMessage->is_admin_reply : false;
                                @endphp

                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-sm relative overflow-hidden
                                    {{ (!$lastUser || !$lastUser->photo) && !$isSupport ?
                                        ($ticket->status === 'closed' ? 'bg-gray-100 dark:bg-gray-700 text-gray-400' :
                                        ($ticket->status === 'open' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-500' :
                                        ($ticket->status === 'pending' ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-500' :
                                        'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500')))
                                        : 'bg-white dark:bg-gray-800'
                                    }}
                                ">
                                    @if($lastUser && $lastUser->profile_photo_path)
                                        <img src="{{ asset('storage/' . $lastUser->profile_photo_path) }}" class="w-full h-full object-cover" title="{{ $lastUser->name }}" />
                                        @if($isSupport)
                                             <div class="absolute bottom-0 right-0 w-4 h-4 rounded-tl-lg bg-primary-500 flex items-center justify-center">
                                                <i class="fa-solid fa-headset text-[8px] text-white"></i>
                                            </div>
                                        @endif
                                    @else
                                        @if($isSupport)
                                             <div class="w-full h-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center">
                                                <i class="fa-solid fa-headset text-xl"></i>
                                             </div>
                                        @else
                                            <i class="fa-solid {{ $ticket->status === 'closed' ? 'fa-lock' : 'fa-ticket' }} text-xl"></i>
                                        @endif
                                    @endif
                                </div>

                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">#{{ $ticket->id }}</span>
                                        <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                                        <span class="text-[10px] font-bold text-gray-400">{{ $ticket->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <h4 class="text-base font-bold text-gray-900 dark:text-white group-hover:text-primary-600 transition-colors mb-1">{{ $ticket->subject }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1">
                                        {{ $ticket->messages->first()->message ?? __('No description...') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Status & Arrow -->
                            <div class="flex flex-col items-end gap-2 shrink-0">
                                @php
                                    $statusConfig = [
                                        'open' => ['bg' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300', 'text' => __('Open')],
                                        'pending' => ['bg' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300', 'text' => __('Pending')],
                                        'answered' => ['bg' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300', 'text' => __('Answered')],
                                        'closed' => ['bg' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400', 'text' => __('Closed')],
                                    ];
                                    $config = $statusConfig[$ticket->status] ?? $statusConfig['closed'];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $config['bg'] }}">
                                    {{ $config['text'] }}
                                </span>
                                <i class="fa-solid fa-chevron-right text-gray-300 group-hover:text-primary-500 transition-colors text-sm"></i>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-12 text-center flex flex-col items-center justify-center">
                        <div class="w-20 h-20 bg-gray-50 dark:bg-gray-700/50 rounded-full flex items-center justify-center mb-4">
                            <i class="fa-solid fa-inbox text-4xl text-gray-300 dark:text-gray-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ __('No tickets found') }}</h3>
                        <p class="text-gray-500 text-sm max-w-xs mx-auto mb-6">{{ __('You haven\'t opened any support requests yet. Need help?') }}</p>
                        <a href="{{ route('user.tickets.create') }}" class="text-sm font-bold text-primary-600 hover:text-primary-700 uppercase tracking-widest hover:underline">{{ __('Open first ticket') }}</a>
                    </div>
                @endforelse
            </div>

            <div class="pt-4">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
