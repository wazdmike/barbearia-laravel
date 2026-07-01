<!DOCTYPE html>
<html lang="pt" class="h-full bg-zinc-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberVibe | Painel de Gestão</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-zinc-950 text-zinc-100 flex flex-col font-sans antialiased">

    <!-- Topbar / Menu de Navegação -->
    <nav class="bg-zinc-900 border-b border-zinc-800 px-6 py-4 flex items-center justify-between shadow-md">
        <div class="flex items-center space-x-3">
            <span class="text-amber-500 font-bold text-xl tracking-widest">BARBERVIBE</span>
            <span
                class="text-[10px] uppercase tracking-wider px-2 py-1 bg-zinc-800 text-zinc-400 rounded-md font-semibold">
                {{ auth()->user()->role === 'admin' ? 'Administrador' : 'Barbeiro' }}
            </span>
        </div>
        <div class="flex items-center space-x-6">
            <span class="text-zinc-300 text-sm hidden sm:inline">Bem-vindo, <strong
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
    <main class="flex-1 max-w-6xl w-full mx-auto p-4 sm:p-6 space-y-6">

        <!-- Header Dinâmico com Ações Rápidas -->
        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-zinc-900 p-6 rounded-2xl border border-zinc-800 shadow-2xl">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-amber-500 font-bold">Painel de Controle</p>
                <h1 class="text-2xl font-bold text-zinc-100 mt-1">Agenda Geral de Atendimentos</h1>
            </div>

            <!-- Botões de Gerenciamento Administrativo (Apenas para Administradores) -->
            @if(auth()->user()->role === 'admin')
                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- BOTÃO NOVO: Gerenciar Barbeiros -->
                    <a href="{{ route('barbers.index') }}"
                        class="inline-flex items-center justify-center gap-2 bg-zinc-800 hover:bg-zinc-750 text-amber-500 border border-zinc-700 hover:border-amber-500 font-bold py-2.5 px-5 rounded-xl transition-all duration-200">
                        <span>👥 Gerenciar Barbeiros</span>
                    </a>

                    <!-- Botão de Serviços existente -->
                    <a href="{{ route('services.index') }}"
                        class="inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-zinc-950 font-bold py-2.5 px-5 rounded-xl transition-all duration-200 shadow-lg shadow-amber-500/10 hover:shadow-amber-500/20 active:scale-[0.98]">
                        <span>⚙️ Gerenciar Serviços</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- Alertas de Feedback de Ações -->
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

        <!-- Tabela Principal de Atendimentos -->
        <div class="bg-zinc-900 rounded-2xl border border-zinc-800 overflow-hidden shadow-2xl">
            <div class="px-6 py-5 border-b border-zinc-800 bg-zinc-900/50 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-zinc-100 text-base">Controle de Horários</h3>
                    <p class="text-xs text-zinc-400 mt-1">
                        {{ auth()->user()->role === 'admin' ? 'Todos os agendamentos registrados no sistema' : 'Seus agendamentos do dia' }}
                    </p>
                </div>
                <span class="text-xs bg-zinc-800 text-zinc-300 px-3 py-1 rounded-full font-semibold">
                    {{ $appointments->total() }} marcações
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-zinc-950/40 text-zinc-400 text-xs uppercase tracking-wider border-b border-zinc-800">
                            <th class="px-6 py-4 font-semibold">Cliente</th>
                            <th class="px-6 py-4 font-semibold">Horário / Data</th>
                            <th class="px-6 py-4 font-semibold">Serviço / Preço</th>
                            <th class="px-6 py-4 font-semibold">Barbeiro</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold text-right">Ações de Controle</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/60">
                        @forelse($appointments as $appointment)
                            <tr class="hover:bg-zinc-850/30 transition-colors">
                                <!-- Cliente -->
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-zinc-100">{{ $appointment->client->name }}</div>
                                    <div class="text-xs text-zinc-400 mt-0.5">{{ $appointment->client->email }}</div>
                                </td>
                                <!-- Data e Horário -->
                                <td class="px-6 py-4 text-sm font-medium text-zinc-200">
                                    {{ $appointment->date_time->format('d/m/Y') }} às
                                    {{ $appointment->date_time->format('H:i') }}
                                </td>
                                <!-- Serviço e Preço -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-zinc-300">{{ $appointment->service->name }}</div>
                                    <div class="text-xs text-amber-500 font-bold mt-0.5">R$
                                        {{ number_format($appointment->service->price, 2, ',', '.') }}</div>
                                </td>
                                <!-- Barbeiro -->
                                <td class="px-6 py-4 text-sm text-zinc-300 font-medium">
                                    {{ $appointment->barber->name }}
                                </td>
                                <!-- Status Badge -->
                                <td class="px-6 py-4 text-sm">
                                    @if($appointment->status === 'pending')
                                        <span
                                            class="px-2.5 py-1 text-xs font-semibold rounded-md bg-amber-950/85 text-amber-400 border border-amber-900/50">Pendente</span>
                                    @elseif($appointment->status === 'confirmed')
                                        <span
                                            class="px-2.5 py-1 text-xs font-semibold rounded-md bg-emerald-950/85 text-emerald-400 border border-emerald-900/50">Confirmado</span>
                                    @elseif($appointment->status === 'completed')
                                        <span
                                            class="px-2.5 py-1 text-xs font-semibold rounded-md bg-zinc-800 text-zinc-300">Concluído</span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 text-xs font-semibold rounded-md bg-rose-950/85 text-rose-400 border border-rose-900/50">Cancelado</span>
                                    @endif
                                </td>
                                <!-- Ações Disponíveis -->
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">

                                        <!-- Se estiver pendente, pode Confirmar ou Cancelar -->
                                        @if($appointment->status === 'pending')
                                            <!-- Confirmar -->
                                            <form action="{{ route('appointments.updateStatus', $appointment) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit"
                                                    class="text-xs font-semibold text-emerald-400 hover:text-white bg-emerald-950/40 border border-emerald-900/50 py-1.5 px-2.5 rounded-lg transition-colors">
                                                    Confirmar
                                                </button>
                                            </form>

                                            <!-- Cancelar -->
                                            <form action="{{ route('appointments.updateStatus', $appointment) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="canceled">
                                                <button type="submit"
                                                    class="text-xs font-semibold text-rose-400 hover:text-white bg-rose-950/40 border border-rose-900/50 py-1.5 px-2.5 rounded-lg transition-colors">
                                                    Recusar
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Se estiver confirmado, pode Concluir ou Cancelar -->
                                        @if($appointment->status === 'confirmed')
                                            <!-- Concluir -->
                                            <form action="{{ route('appointments.updateStatus', $appointment) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit"
                                                    class="text-xs font-semibold text-zinc-100 hover:text-amber-500 bg-amber-500/20 border border-amber-500/40 py-1.5 px-2.5 rounded-lg transition-colors">
                                                    Concluir Serviço
                                                </button>
                                            </form>

                                            <!-- Cancelar -->
                                            <form action="{{ route('appointments.updateStatus', $appointment) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="canceled">
                                                <button type="submit"
                                                    class="text-xs font-semibold text-rose-400 hover:text-rose-300 py-1.5 px-2 rounded-lg transition-colors">
                                                    Cancelar
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Exclusão Permanente (Apenas Administrador para qualquer status) -->
                                        @if(auth()->user()->role === 'admin')
                                            <form action="{{ route('appointments.forceDelete', $appointment) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Tem certeza de que quer excluir DEFINITIVAMENTE este registro de agendamento? Esta ação não pode ser desfeita.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-xs text-rose-500 hover:text-rose-400 font-semibold p-1.5 rounded-lg hover:bg-rose-950/20 transition-colors"
                                                    title="Deletar registro">
                                                    🗑️
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-zinc-500 text-sm">
                                    <div class="text-3xl mb-2">📭</div>
                                    Nenhum agendamento pendente ou realizado foi encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação dos registros -->
            @if($appointments->hasPages())
                <div class="px-6 py-4 border-t border-zinc-800 bg-zinc-950/30">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </main>
</body>

</html>