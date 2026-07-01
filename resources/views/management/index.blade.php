<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberVibe | Gestão</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-zinc-950 text-zinc-100">
    <div class="mx-auto flex max-w-6xl flex-col gap-6 px-4 py-10">
        <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-2xl shadow-black/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-amber-500">Gestão</p>
                    <h1 class="mt-2 text-2xl font-semibold text-zinc-100">Painel administrativo</h1>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="rounded-lg border border-zinc-700 px-4 py-2 text-sm text-zinc-300 transition hover:border-amber-500 hover:text-amber-400">Sair</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>