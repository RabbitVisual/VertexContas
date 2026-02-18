<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Mail Central – Templates</x-slot>

    <x-paneladmin::page title="Templates de E-mail" subtitle="Edite assunto e corpo HTML dos e-mails transacionais (boas-vindas, recuperação de senha, confirmação de assinatura).">
        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-4 text-emerald-600 dark:text-emerald-400 mb-6" role="alert">
                <x-icon name="circle-check" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-600 dark:text-slate-400 uppercase tracking-wider text-xs font-bold sticky top-0">
                        <tr>
                            <th scope="col" class="px-6 py-4">Chave</th>
                            <th scope="col" class="px-6 py-4">Assunto</th>
                            <th scope="col" class="px-6 py-4">Variáveis</th>
                            <th scope="col" class="px-6 py-4">Descrição</th>
                            <th scope="col" class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($templates as $t)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                <td class="px-6 py-4 font-mono text-xs">{{ $t->key }}</td>
                                <td class="px-6 py-4 font-medium">{{ Str::limit($t->subject, 50) }}</td>
                                <td class="px-6 py-4 text-slate-500 dark:text-slate-400 font-mono text-xs">{{ Str::limit($t->variables_hint, 40) }}</td>
                                <td class="px-6 py-4 text-slate-500 dark:text-slate-400">{{ Str::limit($t->description, 60) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.mail.templates.edit', $t) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-[#11C76F]/10 text-[#11C76F] hover:bg-[#11C76F]/20 dark:bg-[#11C76F]/15 dark:hover:bg-[#11C76F]/25 font-bold text-sm transition-colors">
                                        <x-icon name="pen" style="duotone" class="w-4 h-4" /> Editar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">Nenhum template cadastrado. Execute o seeder de e-mail.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.mail.logs.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-[#11C76F]">
                <x-icon name="inbox" style="duotone" class="w-4 h-4" /> Ver logs de e-mail
            </a>
        </div>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
