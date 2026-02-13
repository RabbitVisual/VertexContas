<x-paneladmin::layouts.master>
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-8">
        <a href="{{ route('admin.gateways.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <x-icon name="arrow-left" style="solid" class="text-xl" />
        </a>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 flex items-center">
            Configurar {{ $gateway->name }}
            @if($gateway->slug === 'stripe')
                <x-icon name="stripe" style="brands" class="ml-3 text-primary" />
            @else
                <x-icon name="{{ $gateway->icon }}" style="solid" class="ml-3 text-primary" />
            @endif
        </h1>
    </div>

    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 shadow rounded-lg p-6 max-w-4xl mx-auto">
        <form action="{{ route('admin.gateways.update', $gateway->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <!-- Mode Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ambiente (Mode)</label>
                    <div class="flex items-center space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="mode" value="sandbox" {{ $gateway->mode === 'sandbox' ? 'checked' : '' }} class="form-radio text-primary">
                            <span class="ml-2 text-gray-700 dark:text-gray-300">Sandbox (Teste)</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="mode" value="live" {{ $gateway->mode === 'live' ? 'checked' : '' }} class="form-radio text-primary">
                            <span class="ml-2 text-gray-700 dark:text-gray-300">Live (Produção)</span>
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Sandbox é usado para testes. Mudanças para Live devem ser feitas com cautela.</p>
                </div>

                <!-- API Keys -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">API Key / Public Key</label>
                    <input type="password" name="api_key" value="{{ old('api_key', $gateway->api_key) }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <p class="text-xs text-gray-500 mt-1">Chave pública do gateway.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Secret Key / Access Token</label>
                    <input type="password" name="secret_key" value="{{ old('secret_key', $gateway->secret_key) }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <p class="text-xs text-gray-500 mt-1">Chave secreta ou token de acesso. Armazenado com criptografia.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Webhook Secret</label>
                    <input type="password" name="webhook_secret" value="{{ old('webhook_secret', $gateway->webhook_secret) }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <p class="text-xs text-gray-500 mt-1">Segredo de assinatura do webhook para validação de eventos.</p>
                </div>

                <!-- Webhook URL Info -->
                <div class="bg-gray-50 dark:bg-slate-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        <x-icon name="circle-info" style="solid" class="mr-1" /> Webhook URL
                    </h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Configure esta URL no painel do seu gateway para receber notificações:</p>
                    <div class="flex items-center">
                        <code class="bg-gray-100 dark:bg-slate-800 px-2 py-1 object-contain rounded text-sm w-full select-all">
                            {{ route('webhooks.' . $gateway->slug) }}
                        </code>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ route('webhooks.' . $gateway->slug) }}')" class="ml-2 text-primary hover:text-primary-dark" title="Copiar">
                            <x-icon name="copy" style="solid" />
                        </button>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded transition-colors flex items-center">
                        <x-icon name="save" style="solid" class="mr-2" /> Salvar Configurações
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</x-paneladmin::layouts.master>
