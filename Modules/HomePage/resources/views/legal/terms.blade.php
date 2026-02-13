<x-homepage::layouts.master>
    <x-homepage::layouts.navbar />

    <main class="min-h-screen bg-slate-50 dark:bg-slate-900 py-32 px-4 font-['Poppins']">
        <div class="max-w-4xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-primary/10 rounded-3xl mb-6">
                    <x-icon name="terms" class="text-primary text-4xl" />
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-slate-800 dark:text-white mb-4 tracking-tight">Termos de Uso</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium">Última atualização: {{ date('d/m/Y') }}</p>
            </div>

            <!-- Content Card -->
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-[40px] p-8 md:p-16 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.1)] border border-white dark:border-slate-700">
                <div class="prose prose-slate dark:prose-invert max-w-none space-y-10">

                    <section>
                        <h2 class="text-2xl font-black text-slate-800 dark:text-white flex items-center gap-3 mb-6">
                            <span class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center text-sm font-bold">01</span>
                            Aceitação dos Termos
                        </h2>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                            Ao acessar e usar o VertexContas, você concorda em cumprir e estar vinculado aos seguintes termos e condições de uso. Se você não concordar com qualquer parte destes termos, não deverá acessar o sistema.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-black text-slate-800 dark:text-white flex items-center gap-3 mb-6">
                            <span class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center text-sm font-bold">02</span>
                            Uso do Sistema
                        </h2>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                            O VertexContas é um sistema de gestão financeira pessoal de uso exclusivamente local. Você é responsável por:
                        </p>
                        <ul class="list-disc pl-6 text-slate-600 dark:text-slate-300 space-y-2">
                            <li>Manter a segurança de sua senha e conta.</li>
                            <li>Toda a atividade que ocorre em sua conta.</li>
                            <li>Garantir que os dados inseridos sejam precisos.</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-black text-slate-800 dark:text-white flex items-center gap-3 mb-6">
                            <span class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center text-sm font-bold">03</span>
                            Privacidade e Dados Locais
                        </h2>
                        <div class="p-6 bg-emerald-50 dark:bg-emerald-900/20 rounded-3xl border border-emerald-100 dark:border-emerald-800/30">
                            <div class="flex items-center gap-3 mb-2 text-emerald-600 dark:text-emerald-400 font-bold">
                                <x-icon name="shield-check" />
                                <span>Privacidade é prioridade</span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed">
                                Suas informações financeiras são armazenadas localmente em seu dispositivo. Nós não temos acesso aos seus dados de transações, orçamentos ou metas.
                            </p>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-black text-slate-800 dark:text-white flex items-center gap-3 mb-6">
                            <span class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center text-sm font-bold">04</span>
                            Limitação de Responsabilidade
                        </h2>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                            O sistema é fornecido "como está". Não garantimos que o sistema será ininterrupto ou livre de erros. Em nenhum caso seremos responsáveis por danos decorrentes do uso ou da impossibilidade de usar o sistema.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-black text-slate-800 dark:text-white flex items-center gap-3 mb-6">
                            <span class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center text-sm font-bold">05</span>
                            Alterações nos Termos
                        </h2>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                            Reservamo-nos o direito de modificar estes termos a qualquer momento. O uso continuado do sistema após tais alterações constitui sua aceitação dos novos termos.
                        </p>
                    </section>
                </div>

                <div class="mt-16 pt-10 border-t border-slate-100 dark:border-slate-700 flex flex-col md:flex-row justify-between items-center gap-6">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Dúvidas sobre os termos?</p>
                    <a href="mailto:suporte@vertexcontas.com" class="bg-slate-100 dark:bg-slate-900 hover:bg-primary hover:text-white px-8 py-3 rounded-2xl text-sm font-bold transition-all flex items-center gap-2">
                        <x-icon name="contact" />
                        suporte@vertexcontas.com
                    </a>
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('homepage') }}" class="text-primary font-black flex items-center justify-center gap-2 hover:underline underline-offset-4 decoration-2">
                    <x-icon name="arrow-left" />
                    Voltar para a Home
                </a>
            </div>
        </div>
    </main>

    <x-homepage::layouts.footer />
</x-homepage::layouts.master>
