<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Configurar Gateway</x-slot>

<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-8">
        <a href="{{ route('admin.gateways.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <x-icon name="arrow-left" style="duotone" class="text-xl" />
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
        <div class="mb-8 p-6 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-4 text-emerald-600 dark:text-emerald-400" role="alert">
            <div class="w-12 h-12 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <x-icon name="check" style="duotone" class="text-xl" />
            </div>
            <p class="font-black uppercase tracking-widest text-xs">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-white/5 shadow-2xl p-8 max-w-4xl mx-auto">
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
                    <button type="submit" class="px-8 py-4 bg-[#11C76F] text-white font-black rounded-xl shadow-xl shadow-[#11C76F]/20 hover:bg-[#0EA85A] transition-all flex items-center gap-2 text-sm uppercase tracking-widest">
                        <x-icon name="save" style="duotone" /> Salvar Configurações
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</x-paneladmin::layouts.master>
