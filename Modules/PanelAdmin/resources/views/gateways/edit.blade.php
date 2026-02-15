<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Configurar Gateway</x-slot>

    <x-paneladmin::page title="Configurar {{ $gateway->name }}" subtitle="Chaves de API e ambiente.">
        <x-slot name="header">
            <a href="{{ route('admin.gateways.index') }}" class="inline-flex items-center gap-2 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white font-medium">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4" /> Voltar
            </a>
        </x-slot>

    @if(session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-4 text-emerald-600 dark:text-emerald-400 mb-6" role="alert">
            <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center">
                <x-icon name="check" style="duotone" class="w-5 h-5" />
            </div>
            <p class="font-bold text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <x-paneladmin::card class="max-w-4xl">
        <div class="p-6">
        <form action="{{ route('admin.gateways.update', $gateway->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <!-- Mode Selection -->
                <div>
                    <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] ml-1 mb-2">Ambiente (Mode)</label>
                    <div class="flex items-center gap-6">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="mode" value="sandbox" {{ $gateway->mode === 'sandbox' ? 'checked' : '' }} class="rounded-full border-gray-300 text-primary focus:ring-2 focus:ring-[#11C76F]/20">
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Sandbox (Teste)</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="mode" value="live" {{ $gateway->mode === 'live' ? 'checked' : '' }} class="rounded-full border-gray-300 text-primary focus:ring-2 focus:ring-[#11C76F]/20">
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Live (Produção)</span>
                        </label>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 ml-1">Sandbox é usado para testes. Mudanças para Live devem ser feitas com cautela.</p>
                </div>

                <!-- API Keys -->
                <div>
                    <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] ml-1 mb-2">API Key / Public Key</label>
                    <input type="password" name="api_key" value="{{ old('api_key', $gateway->api_key) }}" class="w-full px-6 py-4 bg-gray-50 dark:bg-white/[0.02] border border-transparent focus:border-[#11C76F]/30 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-medium text-sm transition-all">
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 ml-1">Chave pública do gateway.</p>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] ml-1 mb-2">Secret Key / Access Token</label>
                    <input type="password" name="secret_key" value="{{ old('secret_key', $gateway->secret_key) }}" class="w-full px-6 py-4 bg-gray-50 dark:bg-white/[0.02] border border-transparent focus:border-[#11C76F]/30 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-medium text-sm transition-all">
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 ml-1">Chave secreta ou token de acesso. Armazenado com criptografia.</p>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] ml-1 mb-2">Webhook Secret</label>
                    <input type="password" name="webhook_secret" value="{{ old('webhook_secret', $gateway->webhook_secret) }}" class="w-full px-6 py-4 bg-gray-50 dark:bg-white/[0.02] border border-transparent focus:border-[#11C76F]/30 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-medium text-sm transition-all">
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 ml-1">Segredo de assinatura do webhook para validação de eventos.</p>
                </div>

                <!-- Webhook URL Info -->
                <div class="bg-gray-50 dark:bg-white/[0.02] p-6 rounded-xl border border-gray-100 dark:border-white/5">
                    <h4 class="text-sm font-black text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                        <x-icon name="circle-info" style="duotone" class="text-[#11C76F]" /> Webhook URL
                    </h4>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">Configure esta URL no painel do seu gateway para receber notificações:</p>
                    <div class="flex items-center gap-3">
                        <code class="flex-1 bg-white dark:bg-slate-800/50 px-4 py-3 rounded-xl text-sm font-mono select-all border border-gray-100 dark:border-white/5">
                            {{ route('webhooks.' . $gateway->slug) }}
                        </code>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ route('webhooks.' . $gateway->slug) }}')" class="p-3 rounded-xl bg-[#11C76F]/10 text-[#11C76F] hover:bg-[#11C76F] hover:text-white transition-all shrink-0" title="Copiar">
                            <x-icon name="copy" style="duotone" />
                        </button>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-[#11C76F] text-white font-bold rounded-xl hover:bg-[#0EA85A] transition-colors flex items-center gap-2">
                        <x-icon name="save" style="duotone" class="w-4 h-4" /> Salvar Configurações
                    </button>
                </div>
            </div>
        </form>
        </div>
    </x-paneladmin::card>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
