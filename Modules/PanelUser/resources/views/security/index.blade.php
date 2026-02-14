<x-paneluser::layouts.master :title="__('Security Settings')">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Security & Activity') }}</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('Manage your password and view your login activity.') }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Password Change Column -->
            <div class="lg:col-span-1">
                @if(!session()->has('impersonate_inspection_id'))
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex items-center">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg mr-3">
                            <i class="fa-solid fa-key text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Change Password') }}</h3>
                    </div>

                    <div class="p-6">
                        <form action="{{ route('user.security.password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="space-y-5">
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Current Password') }}</label>
                                    <input type="password" name="current_password" id="current_password" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:text-white sm:text-sm" required />
                                    @error('current_password') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('New Password') }}</label>
                                    <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:text-white sm:text-sm" required />
                                    @error('password') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Confirm New Password') }}</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:text-white sm:text-sm" required />
                                </div>
                            </div>

                            <div class="mt-8">
                                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all hover:shadow-lg transform hover:-translate-y-0.5">
                                    {{ __('Update Security') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @else
                <div class="p-8 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 text-center flex flex-col items-center justify-center h-full">
                    <div class="w-16 h-16 bg-gray-200 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                        <i class="fa-solid fa-lock text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-xs font-black text-gray-500 uppercase tracking-widest leading-tight mb-2">{{ __('Restricted Settings') }}</p>
                    <p class="text-xs text-gray-400 max-w-[200px] mx-auto">{{ __('Support cannot change passwords or access permissions during inspection.') }}</p>
                </div>
                @endif
            </div>

            <!-- Activity Log Column -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                             <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg mr-3">
                                <i class="fa-solid fa-shield-halved text-emerald-600 dark:text-emerald-400"></i>
                            </div>
                            {{ __('Activity Log') }}
                        </h3>
                         <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                             {{ __('Recent Access') }}
                         </span>
                    </div>

                    <div class="p-6">
                        <div class="relative border-l-2 border-gray-200 dark:border-gray-700 ml-3 space-y-8">
                            @php
                                $getBrowserIcon = function($ua) {
                                    $ua = strtolower($ua);
                                    if (str_contains($ua, 'chrome')) return 'fa-brands fa-chrome';
                                    if (str_contains($ua, 'firefox')) return 'fa-brands fa-firefox';
                                    if (str_contains($ua, 'safari') && !str_contains($ua, 'chrome')) return 'fa-brands fa-safari';
                                    if (str_contains($ua, 'edge')) return 'fa-brands fa-edge';
                                    if (str_contains($ua, 'opera')) return 'fa-brands fa-opera';
                                    return 'fa-solid fa-globe';
                                };
                                $getDeviceIcon = function($ua) {
                                     $ua = strtolower($ua);
                                     if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) return 'fa-solid fa-mobile-screen';
                                     return 'fa-solid fa-desktop';
                                };
                            @endphp

                            @forelse($logs as $log)
                                <div class="relative pl-8 group">
                                    <!-- Dot -->
                                    <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full border-2 border-white dark:border-gray-800 bg-emerald-500 shadow-sm z-10 transition-transform group-hover:scale-125"></div>

                                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                        <div>
                                            <h4 class="font-bold text-gray-900 dark:text-white text-sm">
                                                {{ __('Login Successful') }}
                                            </h4>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex flex-col sm:flex-row gap-1 sm:gap-3 items-start sm:items-center">
                                                 <span class="flex items-center" title="{{ $log->user_agent }}">
                                                    <i class="{{ $getDeviceIcon($log->user_agent) }} mr-1.5 text-gray-400"></i>
                                                    <i class="{{ $getBrowserIcon($log->user_agent) }} mr-1.5 text-gray-400"></i>
                                                    <span class="truncate max-w-[200px]">{{ Str::limit($log->user_agent, 40) }}</span>
                                                </span>
                                                <span class="hidden sm:inline text-gray-300">•</span>
                                                <span class="flex items-center">
                                                    <i class="fa-solid fa-network-wired mr-1.5 text-gray-400"></i>
                                                    {{ $log->ip_address }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-right whitespace-nowrap">
                                            <time class="text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-md block sm:inline-block mb-1 sm:mb-0">
                                                {{ $log->created_at->format('d/m/Y H:i') }}
                                            </time>
                                            <p class="text-[10px] text-gray-400">{{ $log->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="pl-8 text-gray-500 text-sm">
                                    {{ __('No activity recorded recently.') }}
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-8 pt-4 border-t border-gray-100 dark:border-gray-700">
                             {{ $logs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
