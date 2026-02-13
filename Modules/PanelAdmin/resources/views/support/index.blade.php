@section('title', 'Visão Geral do Suporte')

<x-paneladmin::layouts.master>
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 dark:bg-blue-900/20 rounded-full mb-6 text-blue-600 dark:text-blue-400">
            <x-icon name="headset" class="w-8 h-8" />
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Central de Suporte</h2>
        <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-8">
            O módulo de suporte (PanelSuporte) será integrado aqui em breve. Você poderá visualizar tickets abertos e métricas de atendimento.
        </p>

        <a href="#" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
            <x-icon name="arrow-right" class="w-4 h-4 mr-2" />
            Ir para Documentação
        </a>
    </div>
</x-paneladmin::layouts.master>
