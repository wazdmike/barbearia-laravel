<!DOCTYPE html>
<html lang="pt-PT" class="h-full bg-zinc-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Serviços - BarberVibe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-full text-zinc-100 flex flex-col font-sans">

    <!-- Topbar / Menu de Navegação -->
    <nav class="bg-zinc-900 border-b border-zinc-800 px-6 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <span class="text-amber-500 font-bold text-xl tracking-wider">BARBERVIBE</span>
            <span class="text-xs uppercase px-2 py-1 bg-zinc-800 text-zinc-400 rounded">Admin</span>
        </div>
        <div class="flex items-center space-x-6">
            <span class="text-zinc-300 text-sm">Olá, {{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-sm text-zinc-400 hover:text-amber-500 transition-colors">Sair</button>
            </form>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Bloco Esquerdo: Formulário de Cadastro (Criar / Editar) -->
        <div class="lg:col-span-1 bg-zinc-900 p-6 rounded-xl border border-zinc-800 shadow-xl self-start">
            <h2 class="text-lg font-bold text-amber-500 mb-6 flex items-center gap-2">
                <span id="form-title">Novo Serviço</span>
            </h2>

            <form id="service-form" action="{{ route('services.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">

                <div>
                    <label for="name"
                        class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Nome do
                        Serviço</label>
                    <input type="text" name="name" id="name" required placeholder="Ex: Degradê Premium"
                        class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2.5 text-zinc-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="price"
                            class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Preço
                            (R$)</label>
                        <input type="number" step="0.01" name="price" id="price" required placeholder="0.00"
                            class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2.5 text-zinc-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label for="duration_minutes"
                            class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Duração
                            (Min)</label>
                        <input type="number" name="duration_minutes" id="duration_minutes" required placeholder="Ex: 30"
                            class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2.5 text-zinc-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="pt-4 flex items-center gap-3">
                    <button type="submit"
                        class="flex-1 bg-amber-500 hover:bg-amber-600 text-zinc-950 font-bold py-2.5 rounded-lg transition-colors shadow-lg shadow-amber-500/10">
                        Salvar Serviço
                    </button>
                    <button type="button" id="btn-cancel" onclick="resetForm()"
                        class="hidden bg-zinc-800 hover:bg-zinc-700 text-zinc-300 py-2.5 px-4 rounded-lg transition-colors">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>

        <!-- Bloco Direito: Listagem dos Serviços Existentes -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Notificações de Sucesso/Erro -->
            @if(session('success'))
                <div
                    class="bg-emerald-950/80 border border-emerald-800 text-emerald-300 p-4 rounded-lg flex items-center justify-between shadow-lg shadow-emerald-950/20">
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div
                    class="bg-rose-950/80 border border-rose-800 text-rose-300 p-4 rounded-lg flex items-center justify-between shadow-lg shadow-rose-950/20">
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-950/80 border border-rose-800 text-rose-300 p-4 rounded-lg shadow-lg">
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-zinc-900 rounded-xl border border-zinc-800 overflow-hidden shadow-xl">
                <div class="px-6 py-4 border-b border-zinc-800 flex items-center justify-between">
                    <h3 class="font-bold text-zinc-100">Serviços Cadastrados</h3>
                    <span class="text-xs text-zinc-400">{{ $services->total() }} serviços ativos</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-zinc-950 text-zinc-400 text-xs uppercase tracking-wider border-b border-zinc-800">
                                <th class="px-6 py-3.5 font-semibold">Nome</th>
                                <th class="px-6 py-3.5 font-semibold">Duração</th>
                                <th class="px-6 py-3.5 font-semibold">Preço</th>
                                <th class="px-6 py-3.5 font-semibold text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800">
                            @forelse($services as $service)
                                <tr class="hover:bg-zinc-850/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-zinc-100">{{ $service->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-300">
                                        {{ $service->duration_minutes }} minutos
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-amber-500">
                                        R$ {{ number_format($service->price, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-3">
                                        <!-- Botão Editar -->
                                        <button onclick="editService({{ json_encode($service) }})"
                                            class="text-xs text-zinc-400 hover:text-amber-500 font-semibold transition-colors">
                                            Editar
                                        </button>

                                        <!-- Formulário de Exclusão -->
                                        <form action="{{ route('services.destroy', $service) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Tem certeza de que deseja remover este serviço?')"
                                                class="text-xs text-zinc-500 hover:text-rose-400 font-semibold transition-colors">
                                                Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-zinc-500 text-sm">
                                        Nenhum serviço disponível no momento. Use o formulário ao lado para criar um!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($services->hasPages())
                    <div class="px-6 py-4 border-t border-zinc-800 bg-zinc-950/50">
                        {{ $services->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- JavaScript para alternância de modo Criação/Edição sem sair da página -->
    <script>
        function editService(service) {
            document.getElementById('form-title').innerText = 'Editar Serviço';
            document.getElementById('service-form').action = `/services/${service.id}`;
            document.getElementById('form-method').value = 'PUT';

            document.getElementById('name').value = service.name;
            document.getElementById('price').value = service.price;
            document.getElementById('duration_minutes').value = service.duration_minutes;

            document.getElementById('btn-cancel').classList.remove('hidden');
        }

        function resetForm() {
            document.getElementById('form-title').innerText = 'Novo Serviço';
            document.getElementById('service-form').action = "{{ route('services.store') }}";
            document.getElementById('form-method').value = 'POST';

            document.getElementById('service-form').reset();
            document.getElementById('btn-cancel').classList.add('hidden');
        }
    </script>
</body>

</html>