<x-paneladmin::layouts.master>
    <div class="h-[calc(100vh-8rem)] flex flex-col animate-in slide-in-from-bottom-6 duration-700">
        <!-- Header -->
        <div class="bg-white dark:bg-[#111111] rounded-[2.5rem] p-6 mb-6 border border-gray-100 dark:border-white/5 shadow-2xl flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <a href="{{ route('admin.support.index') }}" class="w-14 h-14 rounded-2xl bg-gray-50 dark:bg-white/5 flex items-center justify-center text-slate-400 hover:bg-[#11C76F]/10 hover:text-[#11C76F] transition-all hover:scale-105 active:scale-95 shadow-sm group">
                    <x-icon name="arrow-left" class="group-hover:-translate-x-1 transition-transform" />
                </a>
                <div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-black text-[#11C76F] bg-[#11C76F]/10 px-3 py-1 rounded-full uppercase tracking-widest">Support Ticket</span>
                        <span class="text-xs font-bold text-slate-400">#{{ $ticket->id }}</span>
                        @if($ticket->user->isPro())
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-500/20 text-amber-700 dark:text-amber-400 border border-amber-500/30">
                                <x-icon name="crown" style="solid" class="w-3 h-3" /> Cliente PRO
                            </span>
                        @endif
                    </div>
                    <h1 class="text-2xl font-black text-slate-900 dark:text-white line-clamp-1 mt-1 tracking-tight">{{ $ticket->subject }}</h1>
                </div>
            </div>

            <div class="flex items-center gap-4">
                 @php
                    $statusTheme = [
                        'open' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                        'pending' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                        'answered' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400',
                        'closed' => 'bg-slate-100 text-slate-600 dark:bg-white/5 dark:text-slate-400',
                    ];
                    $statusLabel = [
                        'open' => 'Aberto',
                        'pending' => 'Pendente',
                        'answered' => 'Respondido',
                        'closed' => 'Encerrado',
                    ];
                @endphp
                <div class="flex flex-col items-center px-6 py-2 rounded-2xl {{ $statusTheme[$ticket->status] }} border border-current/10">
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] opacity-60">Status Atual</span>
                    <span class="text-xs font-black uppercase tracking-widest mt-0.5">{{ $statusLabel[$ticket->status] }}</span>
                </div>

                <div class="h-10 w-px bg-gray-100 dark:bg-white/5 hidden md:block mx-2"></div>

                <div class="flex items-center gap-3">
                    <button @click="$dispatch('open-modal', 'assign-modal')" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/10 text-slate-500 hover:text-[#11C76F] hover:border-[#11C76F]/50 transition-all flex items-center justify-center shadow-sm group" title="Transferir Chamado">
                        <x-icon name="user-plus" style="duotone" class="group-hover:scale-110 transition-transform" />
                    </button>
                    @if($ticket->assigned_agent_id !== Auth::id())
                        <form action="{{ route('admin.support.takeover', $ticket) }}" method="POST">
                            @csrf
                            <button type="submit" class="h-12 px-6 rounded-2xl bg-[#11C76F] text-white font-black text-xs uppercase tracking-widest hover:bg-[#0EA85A] hover:-translate-y-1 transition-all shadow-xl shadow-[#11C76F]/25 flex items-center gap-3">
                                <x-icon name="handshake" /> Assumir
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Layout -->
        <div class="flex-1 flex flex-col md:flex-row gap-8 overflow-hidden">
            <!-- Chat Side -->
            <div class="flex-1 flex flex-col bg-white dark:bg-[#111111] rounded-[3rem] border border-gray-100 dark:border-white/5 shadow-2xl overflow-hidden relative">
                <!-- Watermark -->
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.02] dark:opacity-[0.01]">
                    <x-icon name="vertex-logo" class="w-96" />
                </div>

                <div class="flex-1 overflow-y-auto p-8 space-y-8 custom-scrollbar relative z-10" id="chat-messages">
                    @forelse($ticket->messages as $msg)
                        <div class="flex {{ $msg->user_id === $ticket->user_id ? 'justify-start' : 'justify-end' }} group animate-in slide-in-from-bottom-2 duration-300">
                            <div class="max-w-[75%] flex flex-col {{ $msg->user_id === $ticket->user_id ? 'items-start' : 'items-end' }}">
                                <div class="flex items-center gap-3 mb-2 px-3">
                                    @if($msg->user_id === $ticket->user_id)
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $msg->user->name }}</span>
                                    @else
                                        <span class="text-[10px] font-black text-[#11C76F] uppercase tracking-widest">Suporte: {{ $msg->user->name }}</span>
                                    @endif
                                    <span class="text-[9px] font-bold text-slate-300">{{ $msg->created_at->format('H:i') }}</span>
                                </div>
                                <div class="px-8 py-5 rounded-[2.5rem] text-sm font-medium leading-relaxed transition-all group-hover:shadow-lg
                                    {{ $msg->user_id === $ticket->user_id
                                        ? 'bg-gray-100 dark:bg-white/5 text-slate-700 dark:text-slate-300 rounded-tl-none border border-transparent dark:border-white/[0.03]'
                                        : ($msg->is_admin_reply
                                            ? 'bg-[#111111] dark:bg-white text-white dark:text-[#111111] shadow-2xl shadow-black/10 rounded-tr-none'
                                            : 'bg-[#11C76F] text-white shadow-xl shadow-[#11C76F]/20 rounded-tr-none') }}">
                                    {!! nl2br(e($msg->message)) !!}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center text-slate-300 px-12 text-center">
                             <div class="w-20 h-20 rounded-full bg-gray-50 dark:bg-white/5 flex items-center justify-center mb-6">
                                <x-icon name="comment-slash" style="duotone" class="text-4xl opacity-20" />
                             </div>
                             <p class="font-black uppercase tracking-widest text-xs">Matenha o foco. Inicie o atendimento.</p>
                             <p class="text-[10px] font-medium text-slate-400 mt-2 max-w-[200px]">Aguardando sua primeira resposta administrativa.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Input Hub -->
                @if($ticket->status !== 'closed')
                    <div class="p-8 bg-gray-50/50 dark:bg-white/[0.02] border-t border-gray-100 dark:border-white/5 relative z-10">
                        <form action="{{ route('admin.support.reply', $ticket) }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="relative group">
                                <textarea name="message" rows="4" required class="w-full px-8 py-6 bg-white dark:bg-[#111111] border-2 border-transparent rounded-[2.5rem] focus:border-[#11C76F]/30 focus:ring-4 focus:ring-[#11C76F]/10 text-slate-800 dark:text-white font-bold text-sm resize-none shadow-sm transition-all placeholder:text-slate-300" placeholder="Digite sua resposta oficial..."></textarea>
                                <div class="absolute right-6 bottom-6 flex items-center gap-3 opacity-40 group-focus-within:opacity-100 transition-opacity">
                                     <x-icon name="markdown" class="text-lg" />
                                </div>
                            </div>

                            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                                <div class="flex items-center gap-6 p-2 bg-white dark:bg-[#111111] rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm px-6">
                                    <div class="relative flex items-center gap-3 group">
                                        <x-icon name="flag-checkered" class="text-slate-400 text-xs" />
                                        <select name="status" class="bg-transparent border-none text-[10px] font-black uppercase tracking-[0.1em] text-slate-500 focus:ring-0 cursor-pointer py-3 pr-10 appearance-none">
                                            <option value="answered" {{ $ticket->status === 'open' ? 'selected' : '' }}>Marcar como Respondido</option>
                                            <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Manter Pendente</option>
                                            <option value="closed">Encerrar Chamado</option>
                                        </select>
                                        <div class="absolute right-0 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 group-hover:text-[#11C76F] transition-colors">
                                            <x-icon name="chevron-down" class="text-[8px]" />
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="w-full md:w-auto px-12 py-5 bg-[#11C76F] text-white font-black rounded-3xl shadow-2xl shadow-[#11C76F]/30 hover:bg-[#0EA85A] hover:-translate-y-1 transition-all flex items-center justify-center gap-4 group">
                                    Enviar Resposta <x-icon name="paper-plane-top" style="duotone" class="group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform" />
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="p-12 bg-gray-50 dark:bg-black/20 text-center relative z-10 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-50 to-transparent dark:from-black/20 dark:to-transparent"></div>
                        <div class="relative">
                            <div class="inline-flex items-center gap-3 px-8 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-full text-xs font-black uppercase tracking-widest shadow-2xl">
                                <x-icon name="lock-keyhole" /> Atendimento Finalizado
                            </div>
                            <p class="text-[10px] font-black text-slate-400 mt-4 tracking-widest uppercase">Este histórico foi arquivado para fins de auditoria.</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar Intel -->
            <div class="w-full md:w-96 space-y-8 overflow-y-auto custom-scrollbar">
                <!-- Customer Profile -->
                <div class="bg-white dark:bg-[#111111] rounded-[3rem] p-8 border border-gray-100 dark:border-white/5 shadow-xl">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                        <x-icon name="user-vneck" class="text-xs" /> Perfil do Cliente
                    </h4>

                    <div class="flex flex-col items-center text-center">
                        <div class="relative mb-6 group cursor-pointer" @click="$dispatch('open-modal', 'edit-photo-modal')">
                            @if($ticket->user->photo)
                                <img src="{{ $ticket->user->photo_url }}" class="w-24 h-24 rounded-[2rem] object-cover shadow-2xl border-4 border-gray-50 dark:border-white/5 group-hover:scale-105 transition-transform">
                            @else
                                <div class="w-24 h-24 rounded-[2rem] bg-[#11C76F]/10 text-[#11C76F] flex items-center justify-center font-black text-3xl border-4 border-[#11C76F]/5 group-hover:scale-105 transition-transform">
                                    {{ substr($ticket->user->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black/40 rounded-[2rem] opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <x-icon name="camera" class="text-white text-xl" />
                            </div>
                            <div class="absolute -bottom-2 -right-2 bg-white dark:bg-[#111111] p-1.5 rounded-xl shadow-lg border border-gray-100 dark:border-white/10">
                                <div class="w-4 h-4 rounded-full bg-emerald-500"></div>
                            </div>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white leading-tight">{{ $ticket->user->name }}</h3>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="px-3 py-1 bg-slate-100 dark:bg-white/5 text-slate-500 rounded-lg text-[9px] font-black uppercase tracking-widest">{{ $ticket->user->getRoleNames()->first() ?? 'Client' }}</span>
                            @if($ticket->user->isPro())
                                <span class="px-3 py-1 bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 rounded-lg text-[9px] font-black uppercase tracking-widest inline-flex items-center gap-1">
                                    <x-icon name="crown" style="solid" class="w-3 h-3" /> Vertex PRO
                                </span>
                            @else
                                <span class="px-3 py-1 bg-slate-100 dark:bg-white/5 text-slate-500 rounded-lg text-[9px] font-black uppercase tracking-widest">Gratuito</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-10 space-y-5">
                        <div class="flex justify-between items-center group cursor-default">
                            <span class="text-[10px] text-slate-400 font-bold uppercase">E-mail Principal</span>
                            <span class="text-xs text-slate-800 dark:text-slate-300 font-black group-hover:text-[#11C76F] transition-colors">{{ $ticket->user->email }}</span>
                        </div>
                         <div class="flex justify-between items-center group cursor-default">
                            <span class="text-[10px] text-slate-400 font-bold uppercase">Contato Watts</span>
                            <span class="text-xs text-slate-800 dark:text-slate-300 font-black group-hover:text-[#11C76F] transition-colors">{{ $ticket->user->phone ?? 'Não informado' }}</span>
                        </div>
                         <div class="flex justify-between items-center group cursor-default">
                            <span class="text-[10px] text-slate-400 font-bold uppercase">CPF Cadastrado</span>
                            <span class="text-xs text-slate-800 dark:text-slate-300 font-black">{{ $ticket->user->cpf ?? '---' }}</span>
                        </div>
                    </div>

                    @if($ticket->user->isPro())
                    <div class="mt-8 p-5 bg-amber-500/10 dark:bg-amber-500/5 rounded-2xl border border-amber-500/20">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 flex items-center justify-center bg-amber-500/20 rounded-xl">
                                <x-icon name="crown" style="solid" class="text-amber-600 dark:text-amber-400 w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-amber-700 dark:text-amber-400 uppercase tracking-widest">Cliente Vertex PRO</p>
                                <p class="text-[11px] text-amber-600/80 dark:text-amber-400/80">Atendimento prioritário • Suporte VIP</p>
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-600 dark:text-slate-400">Este cliente tem direito a suporte prioritário. Priorize a resolução deste chamado.</p>
                    </div>
                    @endif

                    <div class="mt-10 pt-8 border-t border-gray-100 dark:border-white/5">
                        <a href="{{ route('admin.users.show', $ticket->user) }}" class="w-full flex items-center justify-center py-4 rounded-2xl bg-[#111111] dark:bg-white text-white dark:text-[#111111] text-[10px] font-black uppercase tracking-[0.1em] hover:scale-105 active:scale-95 transition-all shadow-xl">
                            Visualizar Dossier Completo
                        </a>
                    </div>
                </div>

                <!-- Active Agent Card -->
                <div class="bg-gradient-to-br from-[#111111] to-[#222222] rounded-[3rem] p-8 text-white shadow-2xl relative overflow-hidden group">
                    <!-- Background Accent -->
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-[#11C76F]/10 rounded-full blur-3xl group-hover:bg-[#11C76F]/20 transition-all duration-700"></div>

                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8 relative z-10">Agente em Atividade</h4>

                    @if($ticket->assignedAgent)
                        <div class="flex items-center gap-5 mb-8 relative z-10">
                            <div class="relative shrink-0">
                                @if($ticket->assignedAgent->photo)
                                    <img src="{{ $ticket->assignedAgent->photo_url }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-white/10">
                                @else
                                    <div class="w-16 h-16 rounded-2xl bg-white/5 text-white flex items-center justify-center font-black text-2xl border border-white/10">
                                        {{ substr($ticket->assignedAgent->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-[#11C76F] border-2 border-[#111111]"></div>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-black text-white text-lg leading-tight">{{ $ticket->assignedAgent->name }}</span>
                                <span class="text-[9px] text-[#11C76F] font-black uppercase tracking-widest mt-1">Nível Admin</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-10 border-2 border-dashed border-white/10 rounded-[2rem] mb-8 relative z-10">
                            <x-icon name="user-robot-xmarks" class="text-3xl text-white/10 mb-2" />
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest italic">Nenhum Agente Designado</p>
                        </div>
                    @endif

                    <button @click="$dispatch('open-modal', 'assign-modal')" class="w-full py-4 bg-[#11C76F] text-white font-black rounded-2xl text-[10px] uppercase tracking-[0.15em] hover:bg-[#0EA85A] transition-all shadow-lg active:scale-95 relative z-10">
                        Transferir Atendimento
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Redesigned Assignment Modal -->
    <x-core::modal name="assign-modal" maxWidth="lg">
        <div class="p-10 space-y-8" x-data="{ selectedAgent: '{{ $ticket->assigned_agent_id }}' }">
            <div class="text-center space-y-2">
                <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Atribuir Agente</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Selecione o especialista ideal para este chamado.</p>
            </div>

            <form action="{{ route('admin.support.assign', $ticket) }}" method="POST" class="space-y-8">
                @csrf
                <div class="grid grid-cols-1 gap-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($agents as $agent)
                        <label class="relative flex items-center p-5 rounded-[1.5rem] border-2 cursor-pointer transition-all duration-300 group
                            " @click="selectedAgent = '{{ $agent->id }}'"
                            :class="selectedAgent == '{{ $agent->id }}' ? 'border-[#11C76F] bg-[#11C76F]/[0.03] shadow-md' : 'border-gray-50 dark:border-white/5 hover:border-slate-200 dark:hover:border-white/10 bg-gray-50/50 dark:bg-white/[0.02]'">

                            <input type="radio" name="agent_id" value="{{ $agent->id }}" x-model="selectedAgent" class="hidden">

                            <div class="relative w-14 h-14 shrink-0 overflow-hidden rounded-xl border-2 transition-all" :class="selectedAgent == '{{ $agent->id }}' ? 'border-[#11C76F]/30' : 'border-transparent'">
                                @if($agent->photo)
                                    <img src="{{ $agent->photo_url }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center font-black text-slate-500 text-lg">
                                        {{ substr($agent->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-primary/20 opacity-0 transition-opacity" :class="selectedAgent == '{{ $agent->id }}' ? 'opacity-100' : ''"></div>
                            </div>

                            <div class="ml-5 flex flex-col flex-1">
                                <span class="text-base font-black transition-colors" :class="selectedAgent == '{{ $agent->id }}' ? 'text-[#11C76F]' : 'text-slate-800 dark:text-white'">{{ $agent->name }}</span>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">{{ $agent->getRoleNames()->first() ?? 'Staff' }}</span>
                                    <div class="w-1 h-1 rounded-full bg-slate-200"></div>
                                    <span class="text-[9px] font-black text-[#11C76F] uppercase tracking-widest" x-show="selectedAgent == '{{ $agent->id }}'">Selecionado</span>
                                </div>
                            </div>

                            <div class="w-6 h-6 rounded-full border-2 transition-all flex items-center justify-center" :class="selectedAgent == '{{ $agent->id }}' ? 'border-[#11C76F] bg-[#11C76F]' : 'border-slate-300 dark:border-white/10'">
                                <x-icon name="check" class="text-[10px] text-white" x-show="selectedAgent == '{{ $agent->id }}'" />
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full py-5 bg-[#11C76F] text-white font-black rounded-2xl shadow-2xl shadow-[#11C76F]/30 hover:bg-[#0EA85A] hover:-translate-y-1 active:scale-95 transition-all text-xs uppercase tracking-[0.2em]">
                        Confirmar Nova Atribuição
                    </button>
                    <button type="button" @click="$dispatch('close-modal', 'assign-modal')" class="w-full py-4 text-slate-400 font-black text-[10px] uppercase tracking-widest hover:text-slate-600 dark:hover:text-white transition-colors">
                        Descartar e Voltar
                    </button>
                </div>
            </form>
        </div>
    </x-core::modal>

    <!-- Photo Update Modal -->
    <x-core::modal name="edit-photo-modal" maxWidth="md">
        <div class="p-10 space-y-8">
            <div class="text-center space-y-2">
                <div class="w-16 h-16 rounded-2xl bg-[#11C76F]/10 text-[#11C76F] flex items-center justify-center mx-auto mb-4">
                    <x-icon name="image-user" style="duotone" class="text-2xl" />
                </div>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Foto do Perfil</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium whitespace-pre-line">Selecione uma nova imagem para o perfil de
                <span class="font-black text-slate-800 dark:text-slate-200">{{ $ticket->user->name }}</span></p>
            </div>

            <form action="{{ route('admin.users.update-photo', $ticket->user) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                <div class="relative group">
                    <input type="file" name="photo" id="photo_input" class="hidden" accept="image/*" onchange="previewImage(this)">
                    <label for="photo_input" class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-200 dark:border-white/10 rounded-[2rem] cursor-pointer hover:border-[#11C76F]/50 hover:bg-[#11C76F]/5 transition-all group">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6" id="preview_placeholder">
                            <x-icon name="cloud-arrow-up" class="text-4xl text-slate-300 group-hover:text-[#11C76F] mb-4 transition-colors" />
                            <p class="mb-2 text-xs font-black text-slate-500 uppercase tracking-widest">Clique para selecionar</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">PNG, JPG ou WEBP (Max 2MB)</p>
                        </div>
                        <img id="image_preview" class="hidden w-full h-full object-cover rounded-[2rem]">
                    </label>
                </div>

                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full py-5 bg-[#11C76F] text-white font-black rounded-2xl shadow-2xl shadow-[#11C76F]/30 hover:bg-[#0EA85A] hover:-translate-y-1 active:scale-95 transition-all text-xs uppercase tracking-[0.2em]">
                        Atualizar Foto Agora
                    </button>
                    <button type="button" @click="$dispatch('close-modal', 'edit-photo-modal')" class="w-full py-4 text-slate-400 font-black text-[10px] uppercase tracking-widest hover:text-slate-600 dark:hover:text-white transition-colors">
                        Manter Atual
                    </button>
                </div>
            </form>
        </div>
    </x-core::modal>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chatMessages = document.getElementById('chat-messages');
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        });

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image_preview').src = e.target.result;
                    document.getElementById('image_preview').classList.remove('hidden');
                    document.getElementById('preview_placeholder').classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @endpush
</x-paneladmin::layouts.master>
