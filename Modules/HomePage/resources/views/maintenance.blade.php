<x-homepage::layouts.master :title="'Em Manutenção - ' . config('app.name')">
    <div class="flex flex-col justify-center items-center px-6 mx-auto min-h-screen xl:px-0 bg-gray-50 dark:bg-gray-900">
        <div class="block mb-5 md:max-w-md w-full">
            @include('homepage::partials.maintenance-illustration')
        </div>
        <div class="text-center xl:max-w-4xl">
            <h1 class="mb-3 text-2xl font-bold leading-tight text-gray-900 sm:text-4xl lg:text-5xl dark:text-white">
                Em Manutenção
            </h1>
            <p class="mb-6 text-base font-normal text-gray-500 md:text-lg dark:text-gray-400">
                Pedimos desculpas pelo inconveniente. Estamos realizando manutenção no momento. Se precisar, você pode sempre
                <a href="{{ route('help-center') }}" class="text-primary-600 hover:underline dark:text-primary-500 font-medium">entrar em contato</a>,
                caso contrário voltaremos em breve!
            </p>
            <a href="{{ route('homepage') }}"
               class="inline-flex items-center justify-center text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 transition-colors">
                <x-icon name="arrow-left" style="solid" class="w-5 h-5 mr-2 -ml-1" />
                Voltar ao início
            </a>
        </div>

        {{-- Footer --}}
        <div class="mt-16 text-center">
            <p class="text-sm text-gray-400 dark:text-gray-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.
            </p>
            @auth
                @if(request()->user()->hasRole('admin'))
                    <div class="mt-4">
                        <a href="{{ route('admin.index') }}"
                           class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 dark:text-primary-500 dark:hover:text-primary-400 text-sm font-medium underline decoration-dotted underline-offset-2 transition-colors">
                            <x-icon name="shield-halved" style="solid" class="w-4 h-4" />
                            Acessar Painel Admin (Bypass Ativo)
                        </a>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</x-homepage::layouts.master>
