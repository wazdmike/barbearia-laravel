<!DOCTYPE html>
<html lang="pt" class="h-full bg-zinc-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberVibe | Gestão</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-zinc-950 text-zinc-100 antialiased">
    <div class="min-h-screen bg-[radial-gradient(circle_at_top_left,rgba(245,158,11,0.18),transparent_32%),linear-gradient(135deg,#09090b_0%,#111827_100%)]">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            <header class="rounded-2xl border border-zinc-800 bg-zinc-900/90 p-5 shadow-2xl shadow-black/30 backdrop-blur">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.35em] text-amber-500">Painel de Gestão</p>
                        <h1 class="mt-2 text-2xl font-semibold text-zinc-100">Bem-vindo, {{ auth()->user()->name }}</h1>
                        <p class="mt-2 text-sm text-zinc-400">Função atual: <span class="font-medium capitalize text-zinc-200">{{ auth()->user()->role }}</span></p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('services.index') }}"
                               class="inline-flex items-center justify-center rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-semibold text-zinc-950 transition-colors duration-200 hover:bg-amber-400">
                                Gerenciar Serviços
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex w-full items-center justify-center rounded-lg border border-zinc-700 px-4 py-2.5 text-sm font-medium text-zinc-300 transition hover:border-amber-500 hover:text-amber-400 sm:w-auto">
                                Sair
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            @if(session('success'))
                <div class="rounded-xl border border-emerald-800 bg-emerald-950/80 p-4 text-sm text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-xl border border-rose-800 bg-rose-950/80 p-4 text-sm text-rose-300">
                    {{ session('error') }}
                </div>
            @endif

            <section class="rounded-2xl border border-zinc-800 bg-zinc-900/90 p-4 shadow-2xl shadow-black/30 sm:p-6">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-zinc-100">Agendamentos</h2>
                        <p class="mt-1 text-sm text-zinc-400">Lista atualizada dos compromissos recebidos.</p>
                    </div>
                    <span class="rounded-full border border-zinc-800 bg-zinc-950 px-3 py-1 text-xs font-medium uppercase tracking-[0.2em] text-zinc-400">
                        {{ $appointments->total() }} total
                    </span>
                </div>

                <div class="overflow-hidden rounded-xl border border-zinc-800">
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-zinc-800">
                            <thead class="bg-zinc-950/80">
                                <tr class="text-left text-xs uppercase tracking-[0.2em] text-zinc-400">
                                    <th class="px-4 py-3 font-semibold">Data/Hora</th>
                                    <th class="px-4 py-3 font-semibold">Cliente</th>
                                    <th class="px-4 py-3 font-semibold">Barbeiro</th>
                                    <th class="px-4 py-3 font-semibold">Serviço</th>
                                    <th class="px-4 py-3 font-semibold">Preço</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-800 bg-zinc-900">
                                @forelse($appointments as $appointment)
                                    <tr class="hover:bg-zinc-800/70">
                                        <td class="px-4 py-3 text-sm text-zinc-200">
                                            {{ $appointment->date_time->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-zinc-200">
                                            {{ $appointment->client?->name ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-zinc-200">
                                            {{ $appointment->barber?->name ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-zinc-200">
                                            {{ $appointment->service?->name ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm font-semibold text-amber-500">
                                            R$ {{ number_format((float) $appointment->service?->price ?? 0, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-12 text-center text-sm text-zinc-500">
                                            Nenhum agendamento para hoje.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="space-y-3 p-3 md:hidden">
                        @forelse($appointments as $appointment)
                            <div class="rounded-xl border border-zinc-800 bg-zinc-950/70 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-zinc-100">{{ $appointment->date_time->format('d/m/Y H:i') }}</p>
                                        <p class="mt-1 text-sm text-zinc-400">{{ $appointment->service?->name ?? '—' }}</p>
                                    </div>
                                    <span class="text-sm font-semibold text-amber-500">
                                        R$ {{ number_format((float) $appointment->service?->price ?? 0, 2, ',', '.') }}
                                    </span>
                                </div>
                                <div class="mt-3 space-y-1 text-sm text-zinc-400">
                                    <p><span class="text-zinc-500">Cliente:</span> {{ $appointment->client?->name ?? '—' }}</p>
                                    <p><span class="text-zinc-500">Barbeiro:</span> {{ $appointment->barber?->name ?? '—' }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl border border-dashed border-zinc-800 bg-zinc-950/60 p-6 text-center text-sm text-zinc-500">
                                Nenhum agendamento para hoje.
                            </div>
                        @endforelse
                    </div>
                </div>

                @if($appointments->hasPages())
                    <div class="mt-4">
                        {{ $appointments->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</body>
</html>