<!DOCTYPE html>
<html lang="pt" class="h-full bg-zinc-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberVibe | Gerenciar Barbeiros</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-zinc-950 text-zinc-100 flex flex-col font-sans antialiased">

    <!-- Topbar / Menu de Navegação -->
    <nav class="bg-zinc-900 border-b border-zinc-800 px-6 py-4 flex items-center justify-between shadow-md">
        <div class="flex items-center space-x-3">
            <span class="text-amber-500 font-bold text-xl tracking-widest">BARBERVIBE</span>
            <span
                class="text-[10px] uppercase tracking-wider px-2 py-1 bg-zinc-800 text-zinc-400 rounded-md font-semibold">Admin</span>
        </div>
        <div class="flex items-center space-x-6">
            <a href="{{ route('management') }}" class="text-sm text-zinc-400 hover:text-amber-500 transition-colors">
                ← Voltar para Painel
            </a>
            <span class="text-zinc-300 text-sm hidden sm:inline">Olá, <strong
                    class="text-white">{{ auth()->user()->name }}</strong></span>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="text-sm bg-zinc-800 border border-zinc-700 hover:border-amber-500 hover:text-amber-400 text-zinc-300 px-4 py-2 rounded-lg transition-all duration-200">
                    Sair
                </button>
            </form>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="flex-1 max-w-6xl w-full mx-auto p-4 sm:p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Bloco Esquerdo: Formulário de Cadastro (Criar / Editar) -->
        <div class="lg:col-span-1 bg-zinc-900 p-6 rounded-2xl border border-zinc-800 shadow-2xl self-start">
            <div class="mb-6">
                <p class="text-xs uppercase tracking-[0.2em] text-amber-500 font-bold">Equipe</p>
                <h2 class="text-xl font-bold text-zinc-100 mt-1" id="form-title">Novo Barbeiro</h2>
            </div>

            <form id="barber-form" action="{{ route('barbers.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">

                <!-- Nome -->
                <div>
                    <label for="name"
                        class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Nome do
                        Barbeiro</label>
                    <input type="text" name="name" id="name" required placeholder="Ex: Lucas Cortador"
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-zinc-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200">
                </div>

                <!-- E-mail -->
                <div>
                    <label for="email"
                        class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">E-mail de
                        Acesso</label>
                    <input type="email" name="email" id="email" required placeholder="barbeiro@barbervibe.com"
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-zinc-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200">
                </div>

                <!-- Senha -->
                <div>
                    <label for="password"
                        class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Senha de
                        Acesso</label>
                    <input type="password" name="password" id="password" placeholder="Mínimo 6 caracteres"
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-zinc-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200">
                    <span class="text-[10px] text-zinc-500 mt-1.5 block leading-relaxed" id="password-hint">Obrigatória
                        no novo cadastro. Na edição, preencha apenas se desejar alterar.</span>
                </div>

                <div class="pt-4 flex items-center gap-3">
                    <button type="submit"
                        class="flex-1 bg-amber-500 hover:bg-amber-600 text-zinc-950 font-bold py-3 rounded-xl transition-all duration-200 shadow-lg shadow-amber-500/10 active:scale-[0.98]">
                        Salvar Profissional
                    </button>
                    <button type="button" id="btn-cancel" onclick="resetForm()"
                        class="hidden bg-zinc-800 hover:bg-zinc-750 text-zinc-300 py-3 px-4 rounded-xl transition-all duration-200">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>

        <!-- Bloco Direito: Listagem dos Barbeiros -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Notificações de Sucesso/Erro -->
            @if(session('success'))
                <div class="bg-emerald-950/80 border border-emerald-800/60 text-emerald-300 p-4 rounded-xl shadow-lg">
                    <span class="text-sm font-medium">✨ {{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-950/80 border border-rose-800/60 text-rose-300 p-4 rounded-xl shadow-lg">
                    <span class="text-sm font-medium">⚠️ {{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-950/80 border border-rose-800/60 text-rose-300 p-4 rounded-xl shadow-lg">
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-zinc-900 rounded-2xl border border-zinc-800 overflow-hidden shadow-2xl">
                <div class="px-6 py-5 border-b border-zinc-800 bg-zinc-900/50 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-zinc-100 text-base">Profissionais Ativos</h3>
                        <p class="text-xs text-zinc-400 mt-1">Lista de barbeiros cadastrados no sistema</p>
                    </div>
                    <span class="text-xs bg-zinc-800 text-zinc-300 px-3 py-1 rounded-full font-semibold">
                        {{ $barbers->total() }} barbeiros
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-zinc-950/40 text-zinc-400 text-xs uppercase tracking-wider border-b border-zinc-800">
                                <th class="px-6 py-4 font-semibold">Nome</th>
                                <th class="px-6 py-4 font-semibold">E-mail</th>
                                <th class="px-6 py-4 font-semibold">Função</th>
                                <th class="px-6 py-4 font-semibold text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/60">
                            @forelse($barbers as $barber)
                                <tr class="hover:bg-zinc-850/30 transition-colors">
                                    <td class="px-6 py-4 font-bold text-zinc-100">
                                        {{ $barber->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-300">
                                        {{ $barber->email }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span
                                            class="px-2.5 py-1 text-xs font-semibold rounded-md bg-zinc-800 text-zinc-300">
                                            Barbeiro
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <!-- Editar -->
                                            <button onclick="editBarber({{ json_encode($barber) }})"
                                                class="text-xs text-zinc-400 hover:text-amber-500 font-semibold transition-colors duration-150">
                                                Editar
                                            </button>

                                            <!-- Excluir -->
                                            <form action="{{ route('barbers.destroy', $barber) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Deseja realmente remover este barbeiro?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-xs text-zinc-600 hover:text-rose-400 font-semibold transition-colors duration-150">
                                                    Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center text-zinc-500 text-sm leading-relaxed">
                                        <div class="text-3xl mb-2">👤</div>
                                        Nenhum barbeiro cadastrado no sistema.<br>
                                        <span class="text-zinc-600 text-xs">Use o formulário ao lado para cadastrar o
                                            primeiro profissional da equipe.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($barbers->hasPages())
                    <div class="px-6 py-4 border-t border-zinc-800 bg-zinc-950/30">
                        {{ $barbers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- JavaScript para alternância de modo Criação/Edição dinamicamente -->
    <script>
        function editBarber(barber) {
            document.getElementById('form-title').innerText = 'Editar Barbeiro';
            document.getElementById('barber-form').action = `/barbers/${barber.id}`;
            document.getElementById('form-method').value = 'PUT';

            document.getElementById('name').value = barber.name;
            document.getElementById('email').value = barber.email;

            // Ajusta o placeholder e a dica da senha
            document.getElementById('password').placeholder = 'Preencha apenas para alterar';
            document.getElementById('password').required = false;
            document.getElementById('password-hint').innerText = 'Deixe em branco para manter a senha atual do profissional.';

            document.getElementById('btn-cancel').classList.remove('hidden');
        }

        function resetForm() {
            document.getElementById('form-title').innerText = 'Novo Barbeiro';
            document.getElementById('barber-form').action = "{{ route('barbers.store') }}";
            document.getElementById('form-method').value = 'POST';

            document.getElementById('barber-form').reset();

            // Reseta placeholder e dica da senha
            document.getElementById('password').placeholder = 'Mínimo 6 caracteres';
            document.getElementById('password').required = true;
            document.getElementById('password-hint').innerText = 'Obrigatória no novo cadastro. Na edição, preencha apenas se desejar alterar.';

            document.getElementById('btn-cancel').classList.add('hidden');
        }
    </script>
</body>

</html>