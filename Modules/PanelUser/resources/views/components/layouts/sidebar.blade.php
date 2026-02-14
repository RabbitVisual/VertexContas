<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="{{ __('Sidebar') }}">
   <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
      <ul class="space-y-2 font-medium">
         <li>
            <a href="{{ route('paneluser.index') }}"
               class="flex items-center p-2 rounded-lg group transition-colors {{ request()->routeIs('paneluser.index') ? 'bg-primary-50 dark:bg-primary-900/10 text-primary-600 dark:text-primary-500' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
               <i class="fa-solid fa-gauge w-5 h-5 transition duration-75 {{ request()->routeIs('paneluser.index') ? 'text-primary-600 dark:text-primary-500' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"></i>
               <span class="ms-3">{{ __('Dashboard') }}</span>
            </a>
         </li>

         <li>
            <a href="{{ route('user.tickets.index') }}"
               class="flex items-center p-2 rounded-lg group transition-colors {{ request()->routeIs('user.tickets.*') ? 'bg-primary-50 dark:bg-primary-900/10 text-primary-600 dark:text-primary-500' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
               <i class="fa-solid fa-ticket w-5 h-5 transition duration-75 {{ request()->routeIs('user.tickets.*') ? 'text-primary-600 dark:text-primary-500' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"></i>
               <span class="flex-1 ms-3 whitespace-nowrap">{{ __('Tickets') }}</span>
            </a>
         </li>

         <li>
            <a href="#"
               class="flex items-center p-2 rounded-lg group transition-colors text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <i class="fa-solid fa-file-invoice-dollar w-5 h-5 transition duration-75 text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
               <span class="flex-1 ms-3 whitespace-nowrap">{{ __('Invoices') }}</span>
            </a>
         </li>

         <li>
            <a href="{{ route('user.subscription.index') }}"
               class="flex items-center p-2 rounded-lg group transition-colors {{ request()->routeIs('user.subscription.*') ? 'bg-primary-50 dark:bg-primary-900/10 text-primary-600 dark:text-primary-500' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
               <i class="fa-solid fa-credit-card w-5 h-5 transition duration-75 {{ request()->routeIs('user.subscription.*') ? 'text-primary-600 dark:text-primary-500' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"></i>
               <span class="flex-1 ms-3 whitespace-nowrap">{{ __('Subscription') }}</span>
            </a>
         </li>

         <li class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-200 dark:border-gray-700"></li>

         <li>
            <a href="{{ route('user.security.index') }}"
               class="flex items-center p-2 rounded-lg group transition-colors {{ request()->routeIs('user.security.*') ? 'bg-primary-50 dark:bg-primary-900/10 text-primary-600 dark:text-primary-500' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
               <i class="fa-solid fa-user-shield w-5 h-5 transition duration-75 {{ request()->routeIs('user.security.*') ? 'text-primary-600 dark:text-primary-500' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"></i>
               <span class="flex-1 ms-3 whitespace-nowrap">{{ __('Security') }}</span>
            </a>
         </li>

         <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                   class="flex items-center p-2 rounded-lg group transition-colors text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                   <i class="fa-solid fa-right-from-bracket w-5 h-5 transition duration-75 text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">{{ __('Sign Out') }}</span>
                </a>
            </form>
         </li>
      </ul>
   </div>
</aside>
