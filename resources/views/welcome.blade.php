<!DOCTYPE html>
<html lang="pt" class="h-full bg-zinc-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberVibe | Estilo, Atitude e Precisão</title>
    <!-- Integração direta com os assets locais via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="min-h-screen bg-zinc-950 text-zinc-100 flex flex-col font-sans antialiased selection:bg-amber-500 selection:text-zinc-900">

    <!-- Barra de Navegação Superior -->
    <header
        class="bg-zinc-900/60 backdrop-blur-md border-b border-zinc-800/80 sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="text-amber-500 font-extrabold text-2xl tracking-widest">BARBERVIBE</span>
            </div>

            <nav class="flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        <!-- Se o utilizador já estiver autenticado, mostramos o botão para o painel correto -->
                        @if(auth()->user()->role === 'client')
                            <a href="{{ route('appointments.index') }}"
                                class="text-sm bg-amber-500 hover:bg-amber-600 text-zinc-950 font-bold py-2.5 px-5 rounded-xl transition-all duration-200 shadow-lg shadow-amber-500/10">
                                As Minhas Marcações
                            </a>
                        @else
                            <a href="{{ route('management') }}"
                                class="text-sm bg-amber-500 hover:bg-amber-600 text-zinc-950 font-bold py-2.5 px-5 rounded-xl transition-all duration-200 shadow-lg shadow-amber-500/10">
                                Painel de Gestão
                            </a>
                        @endif
                    @else
                        <!-- Se for um visitante anónimo -->
                        <a href="{{ route('login') }}"
                            class="text-sm text-zinc-300 hover:text-amber-500 font-semibold transition-colors duration-200">
                            Entrar
                        </a>
                        <a href="{{ route('register') }}"
                            class="text-sm bg-zinc-800 border border-zinc-700 hover:border-amber-500 hover:text-amber-400 text-zinc-300 py-2.5 px-4 rounded-xl transition-all duration-200">
                            Criar Conta
                        </a>
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <!-- Secção Hero Principal -->
    <section class="relative py-20 sm:py-28 overflow-hidden flex items-center justify-center border-b border-zinc-900">
        <!-- Detalhe decorativo de fundo -->
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(245,158,11,0.05),transparent_60%)] pointer-events-none">
        </div>

        <div class="max-w-4xl mx-auto px-6 text-center space-y-8 relative z-10">
            <span
                class="px-3.5 py-1.5 bg-zinc-900 border border-zinc-800 text-amber-500 rounded-full text-xs font-bold uppercase tracking-[0.2em] shadow-inner">
                Experiência Premium de Barbearia
            </span>

            <h1 class="text-4xl sm:text-6xl font-black text-white tracking-tight leading-tight sm:leading-none">
                O corte que define a <span class="text-amber-500">sua atitude</span>.
            </h1>

            <p class="text-base sm:text-xl text-zinc-400 max-w-2xl mx-auto font-medium leading-relaxed">
                Aliamos técnicas clássicas e tendências modernas para criar um estilo único. Faça a sua marcação online
                em segundos e sinta a diferença BarberVibe.
            </p>

            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ auth()->user()->role === 'client' ? route('appointments.index') : route('management') }}"
                        class="w-full sm:w-auto bg-amber-500 hover:bg-amber-600 text-zinc-950 font-black text-base py-4 px-8 rounded-xl transition-all duration-200 shadow-xl shadow-amber-500/10 active:scale-[0.98]">
                        Ir Para o Painel
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="w-full sm:w-auto bg-amber-500 hover:bg-amber-600 text-zinc-950 font-black text-base py-4 px-8 rounded-xl transition-all duration-200 shadow-xl shadow-amber-500/10 active:scale-[0.98]">
                        Agendar Agora
                    </a>
                @endauth
                <a href="#servicos"
                    class="w-full sm:w-auto text-zinc-400 hover:text-white font-semibold py-4 px-8 transition-colors duration-200">
                    Ver Tabela de Preços ↓
                </a>
            </div>
        </div>
    </section>

    <!-- Secção de Destaques / Vantagens -->
    <section class="py-16 max-w-6xl w-full mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8 border-b border-zinc-900">

        <!-- Vantagem 1 -->
        <div
            class="bg-zinc-900 p-6 rounded-2xl border border-zinc-800/80 shadow-lg hover:border-zinc-700 transition-colors duration-250">
            <div class="text-3xl text-amber-500 mb-4">💈</div>
            <h3 class="text-lg font-bold text-zinc-100 mb-2">Profissionais Qualificados</h3>
            <p class="text-sm text-zinc-400 leading-relaxed">
                Os nossos barbeiros dominam desde o clássico corte de navalha aos degradês modernos mais exigentes.
            </p>
        </div>

        <!-- Vantagem 2 -->
        <div
            class="bg-zinc-900 p-6 rounded-2xl border border-zinc-800/80 shadow-lg hover:border-zinc-700 transition-colors duration-250">
            <div class="text-3xl text-amber-500 mb-4">📅</div>
            <h3 class="text-lg font-bold text-zinc-100 mb-2">Agendamento Online</h3>
            <p class="text-sm text-zinc-400 leading-relaxed">
                Esqueça as chamadas telefónicas ou as mensagens. Escolha o seu barbeiro e reserve o seu horário em tempo
                real.
            </p>
        </div>

        <!-- Vantagem 3 -->
        <div
            class="bg-zinc-900 p-6 rounded-2xl border border-zinc-800/80 shadow-lg hover:border-zinc-700 transition-colors duration-250">
            <div class="text-3xl text-amber-500 mb-4">✨</div>
            <h3 class="text-lg font-bold text-zinc-100 mb-2">Ambiente Exclusivo</h3>
            <p class="text-sm text-zinc-400 leading-relaxed">
                Um espaço moderno com música selecionada, poltronas confortáveis e atendimento de excelência.
            </p>
        </div>
    </section>

    <!-- Secção de Serviços e Preços -->
    <section id="servicos" class="py-20 max-w-4xl w-full mx-auto px-6 space-y-12">
        <div class="text-center space-y-3">
            <p class="text-xs uppercase tracking-[0.2em] text-amber-500 font-bold">Menu</p>
            <h2 class="text-3xl font-bold text-zinc-100">Os Nossos Serviços</h2>
            <p class="text-sm text-zinc-400 max-w-lg mx-auto">
                Confira a nossa seleção de serviços especializados de cuidado capilar e estética masculina.
            </p>
        </div>

        <div class="bg-zinc-900 rounded-2xl border border-zinc-800 overflow-hidden shadow-2xl">

            <!-- Verificação Dinâmica de Serviços Cadastrados -->
            @if(isset($services) && $services->count() > 0)
                <div class="divide-y divide-zinc-800/60">
                    @foreach($services as $service)
                        <div class="p-6 flex items-center justify-between hover:bg-zinc-850/20 transition-colors duration-150">
                            <div>
                                <h4 class="font-bold text-zinc-100 text-base sm:text-lg">{{ $service->name }}</h4>
                                <span class="text-xs text-zinc-500 mt-1 block">⏱️ Duração estimada:
                                    {{ $service->duration_minutes }} minutos</span>
                            </div>
                            <div class="text-right">
                                <span class="text-amber-500 font-black text-lg sm:text-xl block">
                                    R$ {{ number_format($service->price, 2, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Amostra Estática de Serviços (Fallback elegante caso o banco de dados esteja vazio) -->
                <div class="divide-y divide-zinc-800/60">
                    <div class="p-6 flex items-center justify-between">
                        <div>
                            <h4 class="font-bold text-zinc-100 text-base sm:text-lg">Corte de Cabelo (Degradê Moderno)</h4>
                            <span class="text-xs text-zinc-500 mt-1 block">⏱️ Duração estimada: 40 minutos</span>
                        </div>
                        <div class="text-right">
                            <span class="text-amber-500 font-black text-lg sm:text-xl block">R$ 45,00</span>
                        </div>
                    </div>
                    <div class="p-6 flex items-center justify-between">
                        <div>
                            <h4 class="font-bold text-zinc-100 text-base sm:text-lg">Barba Completa (Terapia de Toalha
                                Quente)</h4>
                            <span class="text-xs text-zinc-500 mt-1 block">⏱️ Duração estimada: 30 minutos</span>
                        </div>
                        <div class="text-right">
                            <span class="text-amber-500 font-black text-lg sm:text-xl block">R$ 35,00</span>
                        </div>
                    </div>
                    <div class="p-6 flex items-center justify-between">
                        <div>
                            <h4 class="font-bold text-zinc-100 text-base sm:text-lg">Combo Premium (Cabelo + Barba +
                                Sobrancelha)</h4>
                            <span class="text-xs text-zinc-500 mt-1 block">⏱️ Duração estimada: 60 minutos</span>
                        </div>
                        <div class="text-right">
                            <span class="text-amber-500 font-black text-lg sm:text-xl block">R$ 70,00</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Rodapé -->
    <footer class="bg-zinc-950 border-t border-zinc-900 py-12 mt-auto text-center text-zinc-500 text-xs">
        <p>&copy; {{ date('Y') }} BarberVibe. Todos os direitos reservados.</p>
        <p class="text-zinc-600 mt-2 font-mono">Desenvolvido como projeto escolar para o IFSP.</p>
    </footer>

</body>

</html>