<!DOCTYPE html>
<html lang="pt" class="h-full bg-zinc-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberVibe | Meus Agendamentos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-zinc-950 text-zinc-100 flex flex-col font-sans antialiased">

    <!-- Topbar / Menu de Navegação -->
    <nav class="bg-zinc-900 border-b border-zinc-800 px-6 py-4 flex items-center justify-between shadow-md">
        <div class="flex items-center space-x-3">
            <span class="text-amber-500 font-bold text-xl tracking-widest">BARBERVIBE</span>
            <span class="text-[10px] uppercase tracking-wider px-2 py-1 bg-zinc-800 text-zinc-400 rounded-md font-semibold">Cliente</span>
        </div>
        <div class="flex items-center space-x-6">
            <span class="text-zinc-300 text-sm hidden sm:inline">Olá, <strong class="text-white">{{ auth()->user()->name }}</strong></span>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-sm bg-zinc-800 border border-zinc-700 hover:border-amber-500 hover:text-amber-400 text-zinc-300 px-4 py-2 rounded-lg transition-all duration-200">
                    Sair
                </button>
            </form>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="flex-1 max-w-6xl w-full mx-auto p-4 sm:p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Bloco Esquerdo: Formulário de Agendamento (Criar) -->
        <div class="lg:col-span-1 bg-zinc-900 p-6 rounded-2xl border border-zinc-800 shadow-2xl self-start">
            <div class="mb-6">
                <p class="text-xs uppercase tracking-[0.2em] text-amber-500 font-bold">Reserva</p>
                <h2 class="text-xl font-bold text-zinc-100 mt-1">Novo Agendamento</h2>
            </div>

            <form action="{{ route('appointments.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Seleção do Serviço -->
                <div>
                    <label for="service_id" class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Selecione o Serviço</label>
                    <select name="service_id" id="service_id" required
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-zinc-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200">
                        <option value="" disabled selected>Escolha um serviço...</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }} (R$ {{ number_format($service->price, 2, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Seleção do Barbeiro -->
                <div>
                    <label for="barber_id" class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Escolha o Barbeiro</label>
                    <select name="barber_id" id="barber_id" required
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-zinc-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200">
                        <option value="" disabled selected>Escolha o profissional...</option>
                        @foreach($barbers as $barber)
                            <option value="{{ $barber->id }}" {{ old('barber_id') == $barber->id ? 'selected' : '' }}>
                                {{ $barber->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Data e Hora -->
                <div>
                    <label for="date_time" class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Data e Hora</label>
                    <input type="datetime-local" name="date_time" id="date_time" required value="{{ old('date_time') }}"
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-zinc-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200">
                    <span class="text-[10px] text-zinc-500 mt-2 block leading-relaxed">Funcionamento: Segunda a Sábado, das 09:00 às 19:00.</span>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-zinc-950 font-bold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-amber-500/10 hover:shadow-amber-500/20 active:scale-[0.98]">
                        Confirmar Marcação
                    </button>
                </div>
            </form>
        </div>

        <!-- Bloco Direito: Histórico e Marcações Ativas (Listagem) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Notificações de Sucesso/Erro -->
            @if(session('success'))
                <div class="bg-emerald-950/80 border border-emerald-800/60 text-emerald-300 p-4 rounded-xl flex items-center justify-between shadow-lg transition-all">
                    <span class="text-sm font-medium">✨ {{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-950/80 border border-rose-800/60 text-rose-300 p-4 rounded-xl flex items-center justify-between shadow-lg transition-all">
                    <span class="text-sm font-medium">⚠️ {{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-950/80 border border-rose-800/60 text-rose-300 p-4 rounded-xl shadow-lg transition-all">
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-zinc-900 rounded-2xl border border-zinc-800 overflow-hidden shadow-2xl">
                <div class="px-6 py-5 border-b border-zinc-800 flex items-center justify-between bg-zinc-900/50">
                    <div>
                        <h3 class="font-bold text-zinc-100 text-base">Seus Agendamentos</h3>
                        <p class="text-xs text-zinc-400 mt-1">Histórico completo de visitas e marcações ativas</p>
                    </div>
                    <span class="text-xs bg-zinc-800 text-zinc-300 px-2.5 py-1 rounded-full font-semibold">
                        {{ $appointments->total() }} no total
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-zinc-950/40 text-zinc-400 text-xs uppercase tracking-wider border-b border-zinc-800">
                                <th class="px-6 py-4 font-semibold">Data / Hora</th>
                                <th class="px-6 py-4 font-semibold">Serviço</th>
                                <th class="px-6 py-4 font-semibold">Barbeiro</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 font-semibold text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/60">
                            @forelse($appointments as $appointment)
                                <tr class="hover:bg-zinc-800/30 transition-colors">
                                    <td class="px-6 py-4 text-sm font-semibold text-zinc-100">
                                        {{ $appointment->date_time->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-zinc-200">{{ $appointment->service->name }}</div>
                                        <div class="text-xs text-amber-500 font-bold mt-0.5">R$ {{ number_format($appointment->service->price, 2, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-300">
                                        {{ $appointment->barber->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($appointment->status === 'pending')
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-amber-950/80 text-amber-400 border border-amber-900/50">Pendente</span>
                                        @elseif($appointment->status === 'confirmed')
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-emerald-950/80 text-emerald-400 border border-emerald-900/50">Confirmado</span>
                                        @elseif($appointment->status === 'completed')
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-zinc-800 text-zinc-300">Concluído</span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-rose-950/80 text-rose-400 border border-rose-900/50">Cancelado</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($appointment->status === 'pending' || $appointment->status === 'confirmed')
                                            <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Tem certeza de que deseja cancelar este agendamento?')"
                                                    class="text-xs text-rose-400 hover:text-rose-300 font-semibold transition-colors duration-150 py-1 px-2 hover:bg-rose-950/30 rounded-md">
                                                    Cancelar
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-zinc-600 font-medium select-none">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-zinc-500 text-sm leading-relaxed">
                                        <div class="text-3xl mb-2">📅</div>
                                        Você ainda não possui nenhum agendamento marcado.<br>
                                        <span class="text-zinc-600 text-xs">Use o painel ao lado para agendar seu corte com nossos profissionais.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginação Padrão com Estilos do Tailwind -->
                @if($appointments->hasPages())
                    <div class="px-6 py-4 border-t border-zinc-800 bg-zinc-950/30">
                        {{ $appointments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>
</body>
</html>