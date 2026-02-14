<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 transition-colors duration-300">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-start rtl:justify-end">
        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 transition-colors">
            <span class="sr-only">{{ __('Open sidebar') }}</span>
            <i class="fa-solid fa-bars w-6 h-6 flex items-center justify-center"></i>
        </button>
        <a href="{{ route('paneluser.index') }}" class="flex ms-2 md:me-24">
          <img src="{{ asset('storage/logos/logo.svg') }}" class="h-8 me-3" alt="{{ __('Logo') }}" />
          <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">Vertex</span>
        </a>
      </div>
      <div class="flex items-center">
          <div class="flex items-center ms-3 space-x-3">

            <!-- Dark Mode Toggler -->
            <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 transition-colors">
                <i id="theme-toggle-dark-icon" class="hidden fa-solid fa-moon w-5 h-5"></i>
                <i id="theme-toggle-light-icon" class="hidden fa-solid fa-sun w-5 h-5"></i>
            </button>

            <!-- Notifications Bell -->
            <button type="button" class="relative inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 transition-colors">
                <i class="fa-solid fa-bell w-6 h-6"></i>
                <span class="sr-only">{{ __('Notifications') }}</span>
                @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                    <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-1 -end-1 dark:border-gray-900 animate-pulse">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </div>
                @endif
            </button>

            <!-- User Menu -->
            <div class="relative" x-data="{ open: false }">
              <button @click="open = !open" type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 transition-all" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                <span class="sr-only">{{ __('Open user menu') }}</span>

                <div x-data="{ loaded: false, src: '{{ auth()->user()->profile_photo_url ?? '' }}' }" class="relative w-8 h-8 rounded-full overflow-hidden">
                    <div x-show="!loaded" class="absolute inset-0 bg-gray-200 dark:bg-gray-700 animate-pulse"></div>
                    @if(auth()->user()->profile_photo_path)
                         <img class="w-8 h-8 rounded-full object-cover"
                             src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
                             alt="user photo"
                             @load="loaded = true"
                             x-show="loaded"
                             x-transition.opacity.duration.300ms
                        >
                    @else
                        <!-- Local Fallback if no photo -->
                        <div x-show="loaded" class="w-full h-full flex items-center justify-center bg-primary-600 text-white text-xs font-bold" x-init="loaded = true">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
              </button>

              <!-- Dropdown menu -->
              <div x-show="open"
                   @click.away="open = false"
                   x-transition:enter="transition ease-out duration-100"
                   x-transition:enter-start="transform opacity-0 scale-95"
                   x-transition:enter-end="transform opacity-100 scale-100"
                   x-transition:leave="transition ease-in duration-75"
                   x-transition:leave-start="transform opacity-100 scale-100"
                   x-transition:leave-end="transform opacity-0 scale-95"
                   class="z-50 absolute right-0 mt-2 w-48 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
                <div class="px-4 py-3" role="none">
                  <p class="text-sm text-gray-900 dark:text-white" role="none">
                    {{ auth()->user()->name }}
                  </p>
                  <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                    {{ auth()->user()->email }}
                  </p>
                </div>
                <ul class="py-1" role="none">
                  <li>
                    <a href="{{ route('paneluser.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">{{ __('Dashboard') }}</a>
                  </li>
                  <li>
                    <a href="{{ route('user.profile.edit') ?? '#' }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">{{ __('Settings') }}</a>
                  </li>
                  <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">{{ __('Sign out') }}</a>
                    </form>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
</nav>

<script>
    // Dark mode toggle logic
    var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    // Change the icons inside the button based on previous settings
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        themeToggleLightIcon.classList.remove('hidden');
    } else {
        themeToggleDarkIcon.classList.remove('hidden');
    }

    var themeToggleBtn = document.getElementById('theme-toggle');

    themeToggleBtn.addEventListener('click', function() {

        // toggle icons inside button
        themeToggleDarkIcon.classList.toggle('hidden');
        themeToggleLightIcon.classList.toggle('hidden');

        // if set via local storage previously
        if (localStorage.getItem('color-theme')) {
            if (localStorage.getItem('color-theme') === 'light') {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            }

        // if NOT set via local storage previously
        } else {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        }

    });
</script>
