<x-homepage::layouts.master>
    <x-homepage::layouts.navbar />

    <main class="min-h-screen bg-slate-50 dark:bg-slate-900 py-32 px-4 font-['Poppins']">
        <div class="max-w-4xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-primary/10 rounded-3xl mb-6">
                    <x-icon name="privacy" class="text-primary text-4xl" />
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-slate-800 dark:text-white mb-4 tracking-tight">Política de Privacidade</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium">Última atualização: {{ date('d/m/Y') }}</p>
            </div>

            <!-- Content Card -->
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-[40px] p-8 md:p-16 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.1)] border border-white dark:border-slate-700">
                <div class="prose prose-slate dark:prose-invert max-w-none space-y-10">

                    <section>
                        <h2 class="text-2xl font-black text-slate-800 dark:text-white flex items-center gap-3 mb-6">
                            <span class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center text-sm font-bold">01</span>
                            Coleta de Informações
                        </h2>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                            Para o funcionamento do VertexContas, coletamos as seguintes informações durante o cadastro:
                        </p>
                        <ul class="list-disc pl-6 text-slate-600 dark:text-slate-300 space-y-2">
                            <li>Nome e Sobrenome</li>
                            <li>Endereço de e-mail</li>
                            <li>CPF (opcional)</li>
                            <li>Data de Nascimento (opcional)</li>
                            <li>Telefone (opcional)</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-black text-slate-800 dark:text-white flex items-center gap-3 mb-6">
                            <span class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center text-sm font-bold">02</span>
                            Armazenamento Local (100% Local)
                        </h2>
                        <div class="p-8 bg-blue-50 dark:bg-blue-900/20 rounded-[32px] border border-blue-100 dark:border-blue-800/30">
                            <h3 class="text-xl font-bold text-blue-800 dark:text-blue-300 mb-4 flex items-center gap-2">
                                <x-icon name="microchip" />
                                Compromisso com sua Localidade
                            </h3>
                            <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                                O VertexContas foi projetado para ser um sistema de armazenamento local. Seus dados financeiros, transações, receitas e despesas são armazenados diretamente no banco de dados local do sistema. Nós não sincronizamos essas informações financeiras sensíveis com nenhum servidor externo ou nuvem.
                            </p>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-black text-slate-800 dark:text-white flex items-center gap-3 mb-6">
                            <span class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center text-sm font-bold">03</span>
                            Segurança da Informação
                        </h2>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                            Implementamos medidas de segurança técnicas e organizacionais para proteger seus dados pessoais. Como o armazenamento é local, a segurança física e digital do dispositivo onde o sistema está instalado é de sua inteira responsabilidade. Recomendamos o uso de senhas fortes e antivírus.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-black text-slate-800 dark:text-white flex items-center gap-3 mb-6">
                            <span class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center text-sm font-bold">04</span>
                            Seus Direitos (LGPD)
                        </h2>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                            Em conformidade com a LGPD (Lei Geral de Proteção de Dados), você tem direito a:
                        </p>
                        <ul class="list-disc pl-6 text-slate-600 dark:text-slate-300 space-y-2">
                            <li>Acessar seus dados pessoais a qualquer momento.</li>
                            <li>Corrigir dados incompletos ou inexatos.</li>
                            <li>Solicitar a exclusão definitiva de sua conta e dados.</li>
                            <li>Portabilidade dos dados (exportação).</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-black text-slate-800 dark:text-white flex items-center gap-3 mb-6">
                            <span class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center text-sm font-bold">05</span>
                            Cookies e Tecnologias
                        </h2>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                            Utilizamos cookies estritamente necessários para manter sua sessão ativa e garantir o funcionamento seguro do login. Não utilizamos cookies de rastreamento de terceiros ou publicidade.
                        </p>
                    </section>
                </div>

                <div class="mt-16 pt-10 border-t border-slate-100 dark:border-slate-700 text-center">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-6">Para assuntos relacionados à privacidade, entre em contato:</p>
                    <a href="mailto:privacidade@vertexcontas.com" class="inline-flex items-center gap-2 bg-slate-100 dark:bg-slate-900 hover:bg-primary-dark hover:text-white px-10 py-4 rounded-3xl text-sm font-black transition-all">
                        <x-icon name="shield-check" />
                        privacidade@vertexcontas.com
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
