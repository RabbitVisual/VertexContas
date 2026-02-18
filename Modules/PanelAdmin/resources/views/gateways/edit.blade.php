<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Configurar {{ $gateway->name }}</x-slot>

    <x-paneladmin::page title="Configurar {{ $gateway->name }}" subtitle="Chaves de API, ambiente e webhook.">
        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-4 text-emerald-600 dark:text-emerald-400 mb-6" role="alert">
                <x-icon name="circle-check" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-center gap-4 text-amber-600 dark:text-amber-400 mb-6" role="alert">
                <x-icon name="triangle-exclamation" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <x-slot name="header">
            <a href="{{ route('admin.gateways.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4" /> Voltar aos Gateways
            </a>
        </x-slot>

        <div class="max-w-3xl space-y-6">
            {{-- Webhook URL (destaque) --}}
            <x-paneladmin::card title="URL de Webhook" subtitle="Configure esta URL no painel do gateway para receber notificações de pagamento.">
                <div class="p-6">
                    <div class="flex items-center gap-3">
                        <code id="webhook-url" class="flex-1 px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-sm font-mono text-slate-800 dark:text-slate-200 break-all select-all">
                            {{ route('webhooks.' . $gateway->slug) }}
                        </code>
                        <button type="button" id="copy-webhook-btn" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-[#11C76F]/10 text-[#11C76F] hover:bg-[#11C76F] hover:text-white transition-colors shrink-0 font-bold text-sm">
                            <x-icon name="copy" style="duotone" class="w-4 h-4" /> <span id="copy-webhook-label">Copiar</span>
                        </button>
                    </div>
                </div>
            </x-paneladmin::card>

            <x-paneladmin::card title="Credenciais e Ambiente" subtitle="Altere apenas os campos que deseja atualizar. Chaves em branco são mantidas.">
                <form action="{{ route('admin.gateways.update', $gateway) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Ambiente (Mode)</label>
                        <div class="flex flex-wrap gap-6">
                            <label class="inline-flex items-center gap-3 cursor-pointer p-4 rounded-xl border-2 transition-colors {{ $gateway->mode === 'sandbox' ? 'border-amber-500/50 bg-amber-50 dark:bg-amber-500/10' : 'border-slate-200 dark:border-slate-600 hover:border-slate-300 dark:hover:border-slate-500' }}">
                                <input type="radio" name="mode" value="sandbox" {{ $gateway->mode === 'sandbox' ? 'checked' : '' }} class="rounded-full border-slate-300 text-[#11C76F] focus:ring-2 focus:ring-[#11C76F]/20">
                                <span class="flex items-center gap-2 font-bold text-slate-700 dark:text-slate-300">
                                    <x-icon name="flask" style="duotone" class="w-5 h-5 text-amber-500" /> Sandbox (Teste)
                                </span>
                            </label>
                            <label class="inline-flex items-center gap-3 cursor-pointer p-4 rounded-xl border-2 transition-colors {{ $gateway->mode === 'live' ? 'border-blue-500/50 bg-blue-50 dark:bg-blue-500/10' : 'border-slate-200 dark:border-slate-600 hover:border-slate-300 dark:hover:border-slate-500' }}">
                                <input type="radio" name="mode" value="live" {{ $gateway->mode === 'live' ? 'checked' : '' }} class="rounded-full border-slate-300 text-[#11C76F] focus:ring-2 focus:ring-[#11C76F]/20">
                                <span class="flex items-center gap-2 font-bold text-slate-700 dark:text-slate-300">
                                    <x-icon name="bolt" style="duotone" class="w-5 h-5 text-blue-500" /> Live (Produção)
                                </span>
                            </label>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Use Sandbox para testes. Ative Live apenas quando estiver pronto para cobranças reais.</p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">API Key / Public Key</label>
                        <input type="password" name="api_key" value="{{ old('api_key') }}" autocomplete="off" placeholder="{{ $gateway->api_key ? '•••••••• (deixe em branco para manter)' : 'Cole a chave pública' }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        <p class="text-xs text-slate-500 dark:text-slate-400">Chave pública. Deixe em branco para não alterar o valor atual.</p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Secret Key / Access Token</label>
                        <input type="password" name="secret_key" value="{{ old('secret_key') }}" autocomplete="off" placeholder="{{ $gateway->secret_key ? '•••••••• (deixe em branco para manter)' : 'Cole a chave secreta' }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        <p class="text-xs text-slate-500 dark:text-slate-400">Chave secreta. Armazenada criptografada. Deixe em branco para não alterar.</p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Webhook Secret</label>
                        <input type="password" name="webhook_secret" value="{{ old('webhook_secret') }}" autocomplete="off" placeholder="{{ $gateway->webhook_secret ? '•••••••• (deixe em branco para manter)' : 'Cole o segredo do webhook' }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        <p class="text-xs text-slate-500 dark:text-slate-400">Segredo para validar assinaturas do webhook. Recomendado em produção (ex.: Stripe).</p>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-[#11C76F] hover:bg-[#0EA85A] text-white font-bold rounded-xl transition-colors">
                            <x-icon name="floppy-disk" style="duotone" class="w-5 h-5" /> Salvar Configurações
                        </button>
                    </div>
                </form>
            </x-paneladmin::card>
        </div>
    </x-paneladmin::page>

    @push('scripts')
    <script>
        document.getElementById('copy-webhook-btn')?.addEventListener('click', function() {
            var url = document.getElementById('webhook-url');
            var label = document.getElementById('copy-webhook-label');
            if (url && label && navigator.clipboard) {
                navigator.clipboard.writeText(url.innerText.trim());
                label.textContent = 'Copiado!';
                setTimeout(function() { label.textContent = 'Copiar'; }, 2000);
            }
        });
    </script>
    @endpush
</x-paneladmin::layouts.master>
